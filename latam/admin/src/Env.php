<?php

class Env
{
    public static function load($path)
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new RuntimeException('No se pudo leer el archivo .env');
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            throw new RuntimeException('No se pudo leer el archivo .env');
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            if ($key === '') {
                continue;
            }

            // Quitar comillas simples/dobles envolventes
            if (
                strlen($value) >= 2 &&
                (($value[0] === '"' && substr($value, -1) === '"') ||
                 ($value[0] === "'" && substr($value, -1) === "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv($key . '=' . $value);
        }
    }

    public static function get($key, $default = null)
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        $fromGetenv = getenv($key);
        if ($fromGetenv !== false) {
            return $fromGetenv;
        }

        return $default;
    }
}
