<?php

function assert_true($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException('FAIL: ' . $message);
    }
}

function assert_eq($expected, $actual, $message)
{
    if ($expected !== $actual) {
        throw new RuntimeException(
            'FAIL: ' . $message . ' | expected=' . var_export($expected, true) .
            ' actual=' . var_export($actual, true)
        );
    }
}

function assert_throws(callable $fn, $message)
{
    try {
        $fn();
        throw new RuntimeException('FAIL: ' . $message . ' (no exception)');
    } catch (RuntimeException $e) {
        if (strpos($e->getMessage(), 'FAIL:') === 0 && strpos($e->getMessage(), '(no exception)') !== false) {
            throw $e;
        }
        // expected business exception — ok unless it was our FAIL
        if (strpos($e->getMessage(), 'FAIL:') === 0) {
            throw $e;
        }
    } catch (Throwable $e) {
        // any other throwable counts as thrown — ok
    }
}
