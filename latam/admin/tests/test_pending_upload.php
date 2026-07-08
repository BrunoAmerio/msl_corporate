<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/PendingUpload.php';

$sessionId = 'testsession123';
$dir = PendingUpload::dir();
$validFile = $dir . DIRECTORY_SEPARATOR . $sessionId . '_' . 'abc123';
file_put_contents($validFile, 'test');

$resolved = PendingUpload::resolveValidatedPath($validFile, $sessionId);
assert_eq(realpath($validFile), $resolved, 'valid pending path resolves');

assert_throws(function () use ($dir, $sessionId) {
    PendingUpload::resolveValidatedPath($dir . DIRECTORY_SEPARATOR . 'other_' . 'x', $sessionId);
}, 'rejects wrong session prefix');

assert_throws(function () use ($validFile, $sessionId) {
    PendingUpload::resolveValidatedPath('/etc/passwd', $sessionId);
}, 'rejects path outside pending dir');

@unlink($validFile);

echo "OK test_pending_upload\n";
