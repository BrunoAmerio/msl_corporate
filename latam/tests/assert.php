<?php
function assert_true($cond, $msg) {
    if (!$cond) {
        fwrite(STDERR, "FAIL: $msg\n");
        exit(1);
    }
    echo "OK: $msg\n";
}
function assert_eq($actual, $expected, $msg) {
    assert_true($actual === $expected, $msg . " (expected=" . var_export($expected, true) . ", actual=" . var_export($actual, true) . ")");
}
