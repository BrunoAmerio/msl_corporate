<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/Csrf.php';

$token = Csrf::token();
assert_true(is_string($token) && strlen($token) >= 32, 'token length');
assert_true(Csrf::validate($token), 'valid token accepted');
assert_true(!Csrf::validate('invalid'), 'invalid rejected');
assert_true(!Csrf::validate(null), 'null rejected');

// segundo token() debe devolver el mismo mientras viva la sesión
assert_eq($token, Csrf::token(), 'token stable in session');

echo "OK test_csrf\n";
