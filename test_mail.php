<?php
/**
 * Learnerium — Simple Mail Test
 * Upload to: learnerium.jlm.com.ng/test_mail.php
 * DELETE after testing!
 */

$results = array();
$envVars = array();

// Read .env file
$envFile = dirname(__FILE__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'MAIL_') === 0) {
            $parts = explode('=', $line, 2);
            if (isset($parts[1])) {
                $key = trim($parts[0]);
                $val = trim($parts[1]);
                $envVars[$key] = $val;
            }
        }
    }
    $results[] = array('s' => 'ok', 'm' => '.env file found and read successfully');
} else {
    $results[] = array('s' => 'err', 'm' => '.env file NOT FOUND — please create it in the root folder');
}

// Check PHP version
$results[] = array('s' => 'ok', 'm' => 'PHP Version: ' . PHP_VERSION);

// Test SMTP socket connection
$host = 'mail.jlm.com.ng';
$port = 465;
$socket = @fsockopen('ssl://' . $host, $port, $errno, $errstr, 10);
if ($socket) {
    fclose($socket);
    $results[] = array('s' => 'ok', 'm' => 'SMTP Socket connected: ssl://' . $host . ':' . $port);
} else {
    $results[] = array('s' => 'err', 'm' => 'SMTP Socket FAILED on ssl://' . $host . ':' . $port . ' — ' . $errstr . ' (' . $errno . ')');

    // Try port 587 as fallback
    $socket2 = @fsockopen('tls://' . $host, 587, $errno2, $errstr2, 10);
    if ($socket2) {
        fclose($socket2);
        $results[] = array('s' => 'warn', 'm' => 'Port 587 (TLS) works instead — consider changing MAIL_PORT=587 and MAIL_ENCRYPTION=tls in .env');
    } else {
        $results[] = array('s' => 'err', 'm' => 'Port 587 also failed: ' . $errstr2);
    }
}

// Try PHP mail() function
$sent = @mail('learnerium@jlm.com.ng', 'Learnerium Test Email', 'This is a test from test_mail.php', 'From: learnerium@jlm.com.ng');
if ($sent) {
    $results[] = array('s' => 'ok', 'm' => 'PHP mail() function sent successfully!');
} else {
    $results[] = array('s' => 'warn', 'm' => 'PHP mail() returned false (normal if server uses SMTP-only)');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mail Test — Learnerium</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px; margin: 0; }
.wrap { max-width: 700px; margin: 0 auto; }
h1 { color: #1b2299; }
.card { background: #fff; border-radius: 10px; padding: 20px 25px; margin: 15px 0; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.ok { color: #16a34a; font-weight: bold; }
.err { color: #dc2626; font-weight: bold; }
.warn { color: #d97706; font-weight: bold; }
p { margin: 8px 0; font-size: 14px; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
td, th { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
th { background: #f8fafc; font-weight: bold; color: #374151; }
.del { background: #fef9c3; border: 1px solid #fde047; border-radius: 8px; padding: 12px 18px; color: #713f12; font-size: 13px; font-weight: bold; margin-top: 15px; }
pre { background: #f1f5f9; padding: 12px; border-radius: 6px; font-size: 12px; white-space: pre-wrap; }
</style>
</head>
<body>
<div class="wrap">
    <h1>📧 Learnerium Mail Diagnostic</h1>

    <div class="card">
        <h2 style="margin-top:0">🔍 Diagnostic Results</h2>
        <?php foreach ($results as $r): ?>
            <p class="<?php echo $r['s']; ?>">
                <?php
                    if ($r['s'] === 'ok')   echo '✅ ';
                    elseif ($r['s'] === 'err') echo '❌ ';
                    else echo '⚠️ ';
                    echo htmlspecialchars($r['m']);
                ?>
            </p>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($envVars)): ?>
    <div class="card">
        <h2 style="margin-top:0">📋 Current .env MAIL Settings</h2>
        <table>
            <tr><th>Setting</th><th>Value</th></tr>
            <?php foreach ($envVars as $k => $v): ?>
            <tr>
                <td><?php echo htmlspecialchars($k); ?></td>
                <td><?php echo ($k === 'MAIL_PASSWORD') ? str_repeat('*', strlen($v)) : htmlspecialchars($v); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php else: ?>
    <div class="card">
        <p class="err">❌ No MAIL_* settings found in .env. Paste these settings into your .env file:</p>
        <pre>MAIL_MAILER=smtp
MAIL_HOST=mail.jlm.com.ng
MAIL_PORT=465
MAIL_USERNAME=learnerium@jlm.com.ng
MAIL_PASSWORD=~,nzExgp]*v;uw+%
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=learnerium@jlm.com.ng
MAIL_FROM_NAME="Learnerium"</pre>
    </div>
    <?php endif; ?>

    <div class="del">
        ⚠️ <strong>DELETE this file after testing!</strong><br>
        cPanel → File Manager → learnerium.jlm.com.ng → delete <code>test_mail.php</code>
    </div>
</div>
</body>
</html>
