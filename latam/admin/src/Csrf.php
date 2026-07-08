<?php

class Csrf
{
    const SESSION_KEY = '_csrf_token';

    public static function token()
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function validate($token)
    {
        if (!is_string($token) || $token === '') {
            return false;
        }

        if (empty($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }
}
