<?php
/**
 * ============================================================
 * LEARNERIUM — One-Time Production Database Migration Runner
 * ============================================================
 *
 * PURPOSE: Runs all pending Laravel migrations on the live/
 *          production server and then DELETES ITSELF.
 *
 * USAGE:
 *   1. Upload this file to /public/ on your production server
 *   2. Visit: https://yourdomain.com/run-migrations.php
 *   3. This script self-destructs immediately after running
 *
 * SECURITY: Protect with a secret key. Set MIGRATION_SECRET
 *           in your .env or hardcode below. DELETE the file
 *           immediately after use if it didn't self-destruct.
 *
 * ============================================================
 */

// ─── Secret key check ─────────────────────────────────────────────────────────
// Change this secret or use $_ENV['MIGRATION_SECRET'] from your .env
$secret = $_ENV['MIGRATION_SECRET'] ?? getenv('MIGRATION_SECRET') ?? 'LNR-RUN-MIGRATE-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    http_response_code(403);
    exit('❌ Unauthorized. Pass ?key=YOUR_SECRET in the URL.');
}

// ─── Bootstrap Laravel ────────────────────────────────────────────────────────
$laravelRoot = dirname(__DIR__); // One level up from /public

require $laravelRoot . '/vendor/autoload.php';

$app = require_once $laravelRoot . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// ─── Run Migrations ────────────────────────────────────────────────────────────
echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
<title>Learnerium — Production Migration Runner</title>
<style>
  body { font-family: monospace; background: #0f172a; color: #e2e8f0; padding: 2rem; }
  pre  { background: #1e293b; padding: 1.5rem; border-radius: 8px; white-space: pre-wrap; }
  .ok  { color: #34d399; } .warn { color: #fbbf24; } .err { color: #f87171; }
  h1   { color: #818cf8; } h2 { color: #94a3b8; }
</style></head><body>';

echo '<h1>🚀 Learnerium — Production Migration Runner</h1>';
echo '<h2>Running pending migrations...</h2>';
echo '<pre>';

try {
    $exitCode = Artisan::call('migrate', ['--force' => true]);
    $output = Artisan::output();

    if ($exitCode === 0) {
        echo '<span class="ok">' . htmlspecialchars($output ?: 'All migrations ran successfully. Nothing pending.') . '</span>';
    } else {
        echo '<span class="err">Migration exited with code ' . $exitCode . ':' . PHP_EOL;
        echo htmlspecialchars($output) . '</span>';
    }
} catch (\Exception $e) {
    echo '<span class="err">ERROR: ' . htmlspecialchars($e->getMessage()) . '</span>';
}

echo '</pre>';

// ─── Self-Destruct ──────────────────────────────────────────────────────────────
$selfPath = __FILE__;
$deleted  = @unlink($selfPath);

if ($deleted) {
    echo '<pre><span class="ok">✅ Migration runner self-destructed successfully. File deleted.</span></pre>';
} else {
    echo '<pre><span class="warn">⚠️  IMPORTANT: Could not auto-delete this file (' . basename($selfPath) . '). ';
    echo 'Please DELETE it manually from your server immediately!</span></pre>';
}

echo '<p style="color:#64748b;font-size:0.85rem;">© ' . date('Y') . ' Learnerium — Powered by JLM</p>';
echo '</body></html>';
