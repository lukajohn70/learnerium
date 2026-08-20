<?php
/**
 * Learnerium SMTP Mail Diagnostic Tool
 * Upload this file to: /home/gwylvxeo/learnerium.jlm.com.ng/test_mail.php
 * Then visit: https://learnerium.jlm.com.ng/test_mail.php
 * DELETE this file after testing!
 */

// ─── CONFIG ────────────────────────────────────────
$smtp_host     = 'mail.jlm.com.ng';
$smtp_port     = 465;
$smtp_user     = 'learnerium@jlm.com.ng';
$smtp_pass     = '~,nzExgp]*v;uw+%';
$smtp_from     = 'learnerium@jlm.com.ng';
$smtp_to       = 'learnerium@jlm.com.ng'; // send test to yourself
$smtp_name     = 'Learnerium';
// ────────────────────────────────────────────────────

$result = [];
$success = false;

try {
    // 1. Check socket connection
    $socket = @fsockopen("ssl://{$smtp_host}", $smtp_port, $errno, $errstr, 10);
    if (!$socket) {
        $result[] = "❌ SOCKET CONNECT FAILED (ssl://{$smtp_host}:{$smtp_port}): $errstr ($errno)";
    } else {
        fclose($socket);
        $result[] = "✅ Socket connected to ssl://{$smtp_host}:{$smtp_port}";
    }

    // 2. Try sending via PHP mail() as fallback
    $phpmail = @mail($smtp_to, 'Learnerium PHP mail() Test', 'This is a server mail() test.', "From: {$smtp_from}");
    $result[] = $phpmail ? "✅ PHP mail() send attempt succeeded" : "⚠️ PHP mail() failed (expected if SMTP only)";

    // 3. Try PHPMailer if available via Composer
    $autoload = dirname(__FILE__) . '/vendor/autoload.php';
    if (file_exists($autoload)) {
        require $autoload;

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_user;
        $mail->Password   = $smtp_pass;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $smtp_port;
        $mail->setFrom($smtp_from, $smtp_name);
        $mail->addAddress($smtp_to);
        $mail->Subject = '✅ Learnerium SMTP Test';
        $mail->Body    = 'This is a test email from the Learnerium SMTP diagnostic tool. If you receive this, your mail configuration is working correctly!';
        $mail->send();
        $result[] = "✅ PHPMailer SMTP test email sent successfully to {$smtp_to}!";
        $success = true;
    } else {
        $result[] = "⚠️ vendor/autoload.php not found — PHPMailer test skipped";
    }
} catch (Exception $e) {
    $result[] = "❌ PHPMailer Error: " . $e->getMessage();
}

// 4. Check .env settings
$envFile = dirname(__FILE__) . '/.env';
$envSettings = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'MAIL_') === 0) {
            $parts = explode('=', $line, 2);
            $key = $parts[0];
            $val = isset($parts[1]) ? ($key === 'MAIL_PASSWORD' ? str_repeat('*', strlen($parts[1])) : $parts[1]) : '(not set)';
            $envSettings[$key] = $val;
        }
    }
    $result[] = "✅ .env file found";
} else {
    $result[] = "❌ .env file NOT found! This is the root cause.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Learnerium Mail Diagnostic</title>
<style>
  body { font-family: sans-serif; background: #f8fafc; padding: 2rem; color: #1e293b; }
  h1 { color: #1b2299; }
  .card { background: white; border-radius: 1rem; padding: 1.5rem; margin: 1rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
  .ok { color: #16a34a; font-weight: bold; }
  .err { color: #dc2626; font-weight: bold; }
  .warn { color: #d97706; font-weight: bold; }
  pre { background: #f1f5f9; padding: 1rem; border-radius: 0.5rem; font-size: 0.8rem; overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; }
  td, th { padding: 0.5rem 1rem; border-bottom: 1px solid #e2e8f0; font-size: 0.85rem; text-align: left; }
  th { background: #f1f5f9; font-weight: bold; }
  .banner { padding: 1rem 1.5rem; border-radius: 0.75rem; font-weight: bold; margin-bottom: 1rem; }
  .banner.success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
  .banner.failure { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
</style>
</head>
<body>
<h1>📧 Learnerium SMTP Diagnostic Tool</h1>

<div class="banner <?= $success ? 'success' : 'failure' ?>">
    <?= $success ? '🎉 SMTP is working! Test email sent to ' . htmlspecialchars($smtp_to) : '⚠️ SMTP test did not complete successfully. See details below.' ?>
</div>

<div class="card">
    <h2>🔍 Test Results</h2>
    <?php foreach ($result as $line): ?>
        <?php
            if (strpos($line, '✅') !== false) $cls = 'ok';
            elseif (strpos($line, '❌') !== false) $cls = 'err';
            else $cls = 'warn';
        ?>
        <p class="<?= $cls ?>"><?= htmlspecialchars($line) ?></p>
    <?php endforeach; ?>
</div>

<?php if ($envSettings): ?>
<div class="card">
    <h2>📋 Live .env MAIL_* Settings</h2>
    <table>
        <tr><th>Key</th><th>Value</th></tr>
        <?php foreach ($envSettings as $k => $v): ?>
        <tr>
            <td><?= htmlspecialchars($k) ?></td>
            <td><?= htmlspecialchars($v) ?: '<span style="color:#dc2626">(empty)</span>' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<div class="card">
    <h2>⚙️ Expected .env Settings</h2>
    <pre>
MAIL_MAILER=smtp
MAIL_HOST=mail.jlm.com.ng
MAIL_PORT=465
MAIL_USERNAME=learnerium@jlm.com.ng
MAIL_PASSWORD=~,nzExgp]*v;uw+%
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=learnerium@jlm.com.ng
MAIL_FROM_NAME="Learnerium"
    </pre>
    <p class="err">⚠️ DELETE this file (test_mail.php) from your server after testing!</p>
</div>

</body>
</html>
