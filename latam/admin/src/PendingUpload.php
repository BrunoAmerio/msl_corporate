<?php

class PendingUpload
{
    public static function dir()
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'msl_admin_uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }
        return $dir;
    }

    public static function clear()
    {
        if (empty($_SESSION['pending_upload']['path'])) {
            unset($_SESSION['pending_upload']);
            return;
        }
        $path = $_SESSION['pending_upload']['path'];
        if (is_file($path)) {
            @unlink($path);
        }
        unset($_SESSION['pending_upload']);
    }

    public static function clearSessionFiles($sessionId = null)
    {
        $sessionId = $sessionId ?? session_id();
        $dir = self::dir();
        if (!is_dir($dir)) {
            unset($_SESSION['pending_upload']);
            return;
        }
        $prefix = $sessionId . '_';
        foreach (glob($dir . DIRECTORY_SEPARATOR . $prefix . '*') ?: [] as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
        unset($_SESSION['pending_upload']);
    }

    public static function resolveValidatedPath($path, $sessionId = null)
    {
        $sessionId = $sessionId ?? session_id();
        $realPath = realpath($path);
        if ($realPath === false || !is_file($realPath)) {
            throw new RuntimeException('La confirmación de reemplazo es inválida o expiró.');
        }

        $pendingDir = realpath(self::dir());
        if ($pendingDir === false) {
            throw new RuntimeException('La confirmación de reemplazo es inválida o expiró.');
        }

        $prefix = $pendingDir . DIRECTORY_SEPARATOR;
        if (strncmp($realPath, $prefix, strlen($prefix)) !== 0) {
            throw new RuntimeException('La confirmación de reemplazo es inválida o expiró.');
        }

        $basename = basename($realPath);
        $expectedPrefix = $sessionId . '_';
        if (strncmp($basename, $expectedPrefix, strlen($expectedPrefix)) !== 0) {
            throw new RuntimeException('La confirmación de reemplazo es inválida o expiró.');
        }

        return $realPath;
    }
}
