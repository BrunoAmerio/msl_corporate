<?php

$tests = [
    'test_env.php',
    'test_csrf.php',
    'test_rate_limiter.php',
    'test_auth_service.php',
    'test_ftp_filename.php',
    'test_pending_upload.php',
];

$failed = 0;
foreach ($tests as $test) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . $test;
    passthru('php ' . escapeshellarg($path), $code);
    if ($code !== 0) {
        $failed++;
        fwrite(STDERR, "FAILED: {$test}\n");
    }
}

if ($failed > 0) {
    fwrite(STDERR, "{$failed} test file(s) failed\n");
    exit(1);
}

echo "ALL PASS\n";
