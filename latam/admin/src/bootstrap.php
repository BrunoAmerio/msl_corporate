<?php

require_once __DIR__ . '/Env.php';
require_once __DIR__ . '/Csrf.php';
require_once __DIR__ . '/RateLimiter.php';
require_once __DIR__ . '/AuthService.php';
require_once __DIR__ . '/FtpTarifariosService.php';
require_once __DIR__ . '/PendingUpload.php';

$repoRoot = dirname(__DIR__, 3);
$envPath = $repoRoot . DIRECTORY_SEPARATOR . '.env';

if (is_file($envPath)) {
    Env::load($envPath);
}

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$rateLimitDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'msl_admin_rate_limit';
$rateLimiter = new RateLimiter($rateLimitDir, 5, 900);
$authService = new AuthService($rateLimiter);
$ftpService = new FtpTarifariosService();

function client_ip()
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
