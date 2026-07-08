<?php

require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/FtpTarifariosService.php';

assert_eq('tarifario.pdf', FtpTarifariosService::sanitizeFilename('tarifario.pdf'), 'plain ok');
assert_eq('a b.xlsx', FtpTarifariosService::sanitizeFilename('a b.xlsx'), 'spaces ok');

$invalid = ['', '..', '../x.pdf', 'a/b.pdf', 'a\\b.pdf', "x\0y.pdf"];
foreach ($invalid as $name) {
    $threw = false;
    try {
        FtpTarifariosService::sanitizeFilename($name);
    } catch (InvalidArgumentException $e) {
        $threw = true;
    }
    assert_true($threw, 'should reject: ' . var_export($name, true));
}

echo "OK test_ftp_filename\n";
