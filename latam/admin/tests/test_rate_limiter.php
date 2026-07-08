<?php

require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/RateLimiter.php';

$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'msl_rl_' . uniqid();
mkdir($dir);

$limiter = new RateLimiter($dir, 3, 900);
$ip = '203.0.113.10';

assert_true(!$limiter->isBlocked($ip), 'not blocked initially');
$limiter->registerFailure($ip);
$limiter->registerFailure($ip);
assert_true(!$limiter->isBlocked($ip), 'not blocked before max');
$limiter->registerFailure($ip);
assert_true($limiter->isBlocked($ip), 'blocked after max');
assert_true($limiter->remainingBlockSeconds($ip) > 0, 'remaining > 0');

$limiter->reset($ip);
assert_true(!$limiter->isBlocked($ip), 'unblocked after reset');

// cleanup
foreach (glob($dir . DIRECTORY_SEPARATOR . '*') as $f) {
    @unlink($f);
}
@rmdir($dir);

echo "OK test_rate_limiter\n";
