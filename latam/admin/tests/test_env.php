<?php

require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/Env.php';

$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'msl_env_test_' . uniqid() . '.env';
file_put_contents($tmp, "FOO=bar\n# comment\nBAZ=qux\nEMPTY=\n");

Env::load($tmp);
assert_eq('bar', Env::get('FOO'), 'FOO should be bar');
assert_eq('qux', Env::get('BAZ'), 'BAZ should be qux');
assert_eq('', Env::get('EMPTY'), 'EMPTY should be empty string');
assert_eq('fallback', Env::get('MISSING', 'fallback'), 'default works');

@unlink($tmp);
echo "OK test_env\n";
