#!/bin/bash
# ============================================================
# Learnerium — Sensitive File Cleanup Script
# Removes sensitive files from git tracking & history
# Run from: /Applications/MAMP/htdocs/learnerium
# ============================================================

set -e
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'

echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${YELLOW}   Learnerium — Git Sensitive File Cleanup${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

# Safety check — must be in the correct repo
if [ ! -f "artisan" ]; then
    echo -e "${RED}ERROR: Run this from /Applications/MAMP/htdocs/learnerium${NC}"
    exit 1
fi

echo ""
echo "Step 1 of 5 — Checking git status..."
git status --short

echo ""
echo -e "${YELLOW}WARNING: This will rewrite git history on all branches.${NC}"
read -p "Type 'YES' to proceed: " confirm
if [ "$confirm" != "YES" ]; then
    echo "Aborted."
    exit 0
fi

# ---- STEP 2: Update .gitignore to block future leaks ----
echo ""
echo "Step 2 of 5 — Hardening .gitignore..."

GITIGNORE_ADDITIONS='
# ==========================================
# SENSITIVE FILES — NEVER COMMIT THESE
# ==========================================
.env
.env.*
!.env.example
!to_upload/.env.example
/storage/*.key
/storage/oauth-*.key
to_upload/.env
to_upload/.env.*
!to_upload/.env.example
*.pem
*.p12
*.pfx
*.key
id_rsa
id_rsa.pub
*.secret
firebase-credentials.json
google-credentials.json
serviceAccountKey.json
'

if ! grep -q "SENSITIVE FILES" .gitignore; then
    echo "$GITIGNORE_ADDITIONS" >> .gitignore
    echo -e "${GREEN}OK: .gitignore hardened${NC}"
else
    echo -e "${GREEN}OK: .gitignore already hardened${NC}"
fi

# ---- STEP 3: Remove any accidentally tracked sensitive files ----
echo ""
echo "Step 3 of 5 — Removing tracked sensitive files from index..."

for tracked_file in $(git ls-files | grep -E "\.env$|/\.env$|oauth.*\.key$|\.pem$"); do
    git rm --cached "$tracked_file"
    echo -e "${GREEN}Removed from tracking: $tracked_file${NC}"
done

echo ""
echo "Step 4 of 5 — Checking git history for sensitive files..."

if ! command -v git-filter-repo &> /dev/null; then
    echo -e "${YELLOW}git-filter-repo not installed. Attempting install...${NC}"
    pip3 install git-filter-repo 2>/dev/null || brew install git-filter-repo 2>/dev/null || {
        echo -e "${RED}Please install manually: pip3 install git-filter-repo${NC}"
        SKIP_HISTORY=true
    }
fi

if [ "$SKIP_HISTORY" != "true" ]; then
    echo "Purging .env files from ALL git history..."
    git filter-repo --force \
        --path ".env" --invert-paths \
        --path "to_upload/.env" --invert-paths \
        --path "storage/oauth-private.key" --invert-paths \
        --path "storage/oauth-public.key" --invert-paths \
        2>/dev/null && echo -e "${GREEN}OK: History purged${NC}" || \
        echo -e "${YELLOW}OK: No matching files found in history${NC}"
fi

# ---- STEP 5: Commit and force-push ----
echo ""
echo "Step 5 of 5 — Committing and force-pushing..."

git add .gitignore
git diff --cached --quiet || git commit -m "security: harden .gitignore and remove sensitive file tracking"

echo -e "${YELLOW}Ready to force-push to main, test, and version-3${NC}"
read -p "Type 'PUSH' to continue: " pushconfirm
if [ "$pushconfirm" == "PUSH" ]; then
    git push origin main --force-with-lease
    git push origin test --force-with-lease
    git push origin version-3 --force-with-lease
    echo -e "${GREEN}OK: Force-pushed to all branches${NC}"
else
    echo ""
    echo "Run manually when ready:"
    echo "  git push origin main --force-with-lease"
    echo "  git push origin test --force-with-lease"
    echo "  git push origin version-3 --force-with-lease"
fi

echo ""
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}   CLEANUP COMPLETE${NC}"
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo "NEXT STEPS ON THE PRODUCTION SERVER:"
echo "  1. SSH into server and verify .env is NOT in git:"
echo "       git ls-files | grep .env"
echo "  2. Rotate your credentials:"
echo "       DB_PASSWORD, MAIL_PASSWORD, GEMINI_API_KEY"
echo "       PAYSTACK_SECRET_KEY, PAYSTACK_PUBLIC_KEY"
echo "  3. Regenerate APP_KEY:"
echo "       php artisan key:generate"
echo "  4. Clear caches:"
echo "       php artisan config:clear && php artisan cache:clear"
