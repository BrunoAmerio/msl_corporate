<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/Env.php';
require_once dirname(__DIR__) . '/src/RateLimiter.php';
require_once dirname(__DIR__) . '/src/AuthService.php';

$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'msl_auth_' . uniqid();
mkdir($dir);

$_ENV['ADMIN_USER'] = 'admin_test';
$_ENV['ADMIN_PASSWORD_HASH'] = password_hash('Secret#12345', PASSWORD_DEFAULT);
putenv('ADMIN_USER=admin_test');
putenv('ADMIN_PASSWORD_HASH=' . $_ENV['ADMIN_PASSWORD_HASH']);

$limiter = new RateLimiter($dir, 5, 900);
$auth = new AuthService($limiter);

assert_true(!$auth->isAuthenticated(), 'starts logged out');

$result = $auth->attempt('admin_test', 'wrong', '198.51.100.1');
assert_eq(false, $result['ok'], 'wrong password fails');
assert_eq('invalid', $result['error'], 'invalid error code');

$result = $auth->attempt('admin_test', 'Secret#12345', '198.51.100.1');
assert_eq(true, $result['ok'], 'good credentials');
assert_true($auth->isAuthenticated(), 'authenticated after success');

$auth->logout();
assert_true(!$auth->isAuthenticated(), 'logged out');

// cleanup
foreach (glob($dir . DIRECTORY_SEPARATOR . '*') as $f) {
    @unlink($f);
}
@rmdir($dir);

echo "OK test_auth_service\n";
