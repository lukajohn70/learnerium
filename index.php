<?php
/**
 * Learnerium Root Entry Point Proxy for cPanel / LiteSpeed
 */

define('LARAVEL_START', microtime(true));

// Register The Auto Loader
require __DIR__.'/vendor/autoload.php';

// Turn On The Lights / Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Run The Application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
