<?php
/**
 * Learnerium Automated Server & Deployment Diagnostic Script
 */
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function statusBadge($ok, $text = '') {
    if ($ok) {
        return '<span style="background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:999px; font-weight:bold; font-size:12px;">✅ PASS ' . htmlspecialchars($text) . '</span>';
    }
    return '<span style="background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:999px; font-weight:bold; font-size:12px;">❌ FAIL ' . htmlspecialchars($text) . '</span>';
}

$baseDir = __DIR__;
if (file_exists($baseDir . '/../bootstrap/app.php')) {
    $rootDir = realpath($baseDir . '/..');
} else {
    $rootDir = $baseDir;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learnerium Server Diagnostics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .card { border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        pre { background: #0f172a; color: #38bdf8; padding: 16px; border-radius: 12px; font-size: 13px; max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body class="py-5">
<div class="container max-w-4xl">
    <div class="card bg-white p-5 mb-4">
        <h2 class="text-primary font-bold mb-1">🛠️ Learnerium Deployment Diagnostics</h2>
        <p class="text-muted">Analyzing environment, file structure, database connectivity, and permissions...</p>
        <hr>

        <!-- 1. Environment & Paths -->
        <h4 class="mt-4 text-dark">1. Server Paths & Environment</h4>
        <table class="table table-bordered table-striped mt-2">
            <tr><td><strong>PHP Version</strong></td><td><?= PHP_VERSION ?> <?= statusBadge(version_compare(PHP_VERSION, '7.4.0', '>=')) ?></td></tr>
            <tr><td><strong>Script Location</strong></td><td><code><?= htmlspecialchars(__FILE__) ?></code></td></tr>
            <tr><td><strong>Detected Root Folder</strong></td><td><code><?= htmlspecialchars($rootDir) ?></code></td></tr>
            <tr><td><strong>Document Root</strong></td><td><code><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') ?></code></td></tr>
            <tr><td><strong>Request URI</strong></td><td><code><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') ?></code></td></tr>
        </table>

        <!-- 2. Essential Files Check -->
        <h4 class="mt-4 text-dark">2. Critical Laravel Files Checklist</h4>
        <table class="table table-bordered table-striped mt-2">
            <?php
            $filesToCheck = [
                '.env' => $rootDir . '/.env',
                'vendor/autoload.php' => $rootDir . '/vendor/autoload.php',
                'bootstrap/app.php' => $rootDir . '/bootstrap/app.php',
                'public/index.php' => $rootDir . '/public/index.php',
                'storage/logs' => $rootDir . '/storage/logs',
                'bootstrap/cache' => $rootDir . '/bootstrap/cache',
            ];
            foreach ($filesToCheck as $label => $path):
                $exists = file_exists($path);
            ?>
            <tr>
                <td><strong><?= htmlspecialchars($label) ?></strong></td>
                <td><code><?= htmlspecialchars($path) ?></code></td>
                <td><?= statusBadge($exists) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- 3. Folder Permissions -->
        <h4 class="mt-4 text-dark">3. Folder Write Permissions</h4>
        <table class="table table-bordered table-striped mt-2">
            <?php
            $writablePaths = [
                'storage' => $rootDir . '/storage',
                'storage/framework/views' => $rootDir . '/storage/framework/views',
                'storage/framework/sessions' => $rootDir . '/storage/framework/sessions',
                'storage/logs' => $rootDir . '/storage/logs',
                'bootstrap/cache' => $rootDir . '/bootstrap/cache',
            ];
            foreach ($writablePaths as $label => $path):
                $isWritable = is_dir($path) && is_writable($path);
            ?>
            <tr>
                <td><strong><?= htmlspecialchars($label) ?></strong></td>
                <td><code><?= htmlspecialchars($path) ?></code></td>
                <td><?= statusBadge($isWritable, $isWritable ? 'Writable' : 'Not Writable / Missing') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- 4. PHP Required Extensions -->
        <h4 class="mt-4 text-dark">4. Required PHP Extensions</h4>
        <div class="row g-2 mt-1">
            <?php
            $extensions = ['pdo', 'pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo', 'bcmath'];
            foreach ($extensions as $ext):
                $loaded = extension_loaded($ext);
            ?>
            <div class="col-6 col-md-3">
                <div class="p-2 border rounded text-center bg-light">
                    <small class="fw-bold d-block"><?= $ext ?></small>
                    <?= statusBadge($loaded) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- 5. Database Connection Test -->
        <h4 class="mt-4 text-dark">5. Live Database Connection Test</h4>
        <?php
        $envPath = $rootDir . '/.env';
        if (file_exists($envPath)) {
            $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $envData = [];
            foreach ($envLines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $envData[trim($key)] = trim(trim($value), '"\'');
                }
            }

            $dbHost = $envData['DB_HOST'] ?? '127.0.0.1';
            $dbPort = $envData['DB_PORT'] ?? '3306';
            $dbName = $envData['DB_DATABASE'] ?? '';
            $dbUser = $envData['DB_USERNAME'] ?? '';
            $dbPass = $envData['DB_PASSWORD'] ?? '';

            echo '<div class="p-3 bg-light border rounded mb-3">';
            echo '<small>Host: <code>' . htmlspecialchars($dbHost) . '</code> | DB: <code>' . htmlspecialchars($dbName) . '</code> | User: <code>' . htmlspecialchars($dbUser) . '</code></small>';
            echo '</div>';

            try {
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
                $pdo = new PDO($dsn, $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5
                ]);
                $tableCount = count($pdo->query('SHOW TABLES')->fetchAll());
                echo '<div class="alert alert-success">✅ Database connection successful! Total tables found: <strong>' . $tableCount . '</strong></div>';
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">❌ Database connection failed: <strong>' . htmlspecialchars($e->getMessage()) . '</strong></div>';
            }
        } else {
            echo '<div class="alert alert-warning">⚠️ .env file not found at <code>' . htmlspecialchars($envPath) . '</code></div>';
        }
        ?>

        <!-- 6. Recent Laravel Error Logs -->
        <h4 class="mt-4 text-dark">6. Recent Laravel Error Logs</h4>
        <?php
        $logFile = $rootDir . '/storage/logs/laravel.log';
        if (file_exists($logFile)) {
            $logLines = file($logFile);
            $recentLogs = array_slice($logLines, -30);
            echo '<pre>' . htmlspecialchars(implode('', $recentLogs)) . '</pre>';
        } else {
            echo '<div class="alert alert-secondary">No <code>laravel.log</code> file found yet.</div>';
        }
        ?>
    </div>
</div>
</body>
</html>
