<?php
/**
 * Learnerium Online Database Migration Runner Script
 * 
 * Access this script in your browser once:
 * http://learnerium.jlm.com.ng/setup_db.php?key=LearneriumSetup2026
 */

$securityKey = $_GET['key'] ?? '';

if ($securityKey !== 'LearneriumSetup2026') {
    http_response_code(403);
    die('<div style="font-family:sans-serif; padding:40px; text-align:center;"><h2>🔒 Access Denied</h2><p>Invalid security key provided. Append <code>?key=LearneriumSetup2026</code> to the URL.</p></div>');
}

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo '<!DOCTYPE html><html lang="en"><head><title>Learnerium Database Setup</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '</head><body class="bg-light p-5"><div class="container max-w-2xl bg-white p-5 rounded-4 shadow">';
echo '<h2 class="text-primary mb-3">🚀 Learnerium Database Migration Runner</h2>';

try {
    echo '<div class="alert alert-info">Running <code>artisan migrate --force</code>...</div>';
    
    $status = \Illuminate\Support\Facades\Artisan::call('migrate', [
        '--force' => true,
    ]);
    
    $output = \Illuminate\Support\Facades\Artisan::output();
    
    echo '<pre class="bg-dark text-light p-3 rounded">' . htmlspecialchars($output ?: 'Migration completed successfully with no pending migrations.') . '</pre>';
    echo '<div class="alert alert-success">✅ All database tables created/updated successfully!</div>';
} catch (\Throwable $e) {
    echo '<div class="alert alert-danger">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    echo '<pre class="bg-danger-subtle p-3 rounded">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}

echo '<hr><p class="text-muted small">For security, delete this <code>setup_db.php</code> file after database creation.</p>';
echo '</div></body></html>';
