<?php
/**
 * Learnerium Live SMTP Email Sender Test
 */
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LARAVEL_START', microtime(true));

// Auto-detect vendor autoload and bootstrap path regardless of script location
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
} else {
    die('<div style="font-family:sans-serif; padding:40px; text-align:center;"><h2>❌ Error</h2><p>Could not locate <code>vendor/autoload.php</code>. Please check file path.</p></div>');
}

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$toEmail = $_GET['to'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learnerium SMTP Mail Tester</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container max-w-xl bg-white p-5 rounded-4 shadow">
    <h3 class="text-primary font-bold mb-3">📧 Learnerium Live Mail Tester</h3>
    <p class="text-muted">Test sending emails via SSL SMTP server <code>mail.jlm.com.ng:465</code></p>
    <hr>

    <form method="GET" class="mb-4">
        <div class="mb-3">
            <label class="form-label font-bold">Recipient Email Address</label>
            <input type="email" name="to" class="form-control" placeholder="your-personal@email.com" value="<?= htmlspecialchars($toEmail) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-full py-2.5 fw-bold">🚀 Send Test Verification Email</button>
    </form>

    <?php if ($toEmail): ?>
        <div class="alert alert-info">Sending test email to <strong><?= htmlspecialchars($toEmail) ?></strong>...</div>
        <?php
        try {
            \Illuminate\Support\Facades\Mail::raw("Hello from Learnerium!\n\nThis is a test email sent from your live cPanel SMTP server (learnerium@jlm.com.ng) to confirm that email verification and OTP delivery are 100% operational.", function ($message) use ($toEmail) {
                $message->to($toEmail)
                        ->subject('Learnerium Live Email Test - ' . date('H:i:s'));
            });
            echo '<div class="alert alert-success">✅ <strong>Success!</strong> Test email was sent successfully to <code>' . htmlspecialchars($toEmail) . '</code> via <code>learnerium@jlm.com.ng</code>!</div>';
        } catch (\Throwable $e) {
            echo '<div class="alert alert-danger">❌ <strong>Mail Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<pre class="bg-dark text-danger p-3 rounded mt-2" style="font-size:12px;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
        ?>
    <?php endif; ?>
</div>
</body>
</html>
