<?php

class FtpTarifariosService
{
    private $host;
    private $port;
    private $user;
    private $pass;
    private $remotePath;
    private $passive;

    public function __construct()
    {
        $this->host = Env::get('FTP_HOST', '');
        $this->port = (int) Env::get('FTP_PORT', '21');
        $this->user = Env::get('FTP_USER', '');
        $this->pass = Env::get('FTP_PASS', '');
        $this->remotePath = rtrim(Env::get('FTP_REMOTE_PATH', '/'), '/');
        $this->passive = strtolower((string) Env::get('FTP_PASSIVE', 'true')) !== 'false';
    }

    public static function sanitizeFilename($name)
    {
        if (!is_string($name) || $name === '') {
            throw new InvalidArgumentException('Nombre de archivo inválido');
        }

        if (strpos($name, "\0") !== false) {
            throw new InvalidArgumentException('Nombre de archivo inválido');
        }

        $base = basename(str_replace('\\', '/', $name));
        if ($base === '' || $base === '.' || $base === '..') {
            throw new InvalidArgumentException('Nombre de archivo inválido');
        }

        if ($base !== $name || strpos($name, '/') !== false || strpos($name, '\\') !== false) {
            // Si venía con path, basename puede "arreglarlo"; igual rechazamos paths explícitos
            if (strpos($name, '/') !== false || strpos($name, '\\') !== false || $name === '..') {
                throw new InvalidArgumentException('Nombre de archivo inválido');
            }
        }

        if (strpos($base, '..') !== false) {
            throw new InvalidArgumentException('Nombre de archivo inválido');
        }

        return $base;
    }

    private function connect()
    {
        if ($this->host === '' || $this->user === '') {
            error_log('MSL admin FTP: incomplete configuration');
            throw new RuntimeException('Configuración FTP incompleta');
        }

        $conn = @ftp_connect($this->host, $this->port, 20);
        if ($conn === false) {
            error_log('MSL admin FTP: connection failed');
            throw new RuntimeException('No se pudo conectar al servidor de archivos');
        }

        if (!@ftp_login($conn, $this->user, $this->pass)) {
            @ftp_close($conn);
            error_log('MSL admin FTP: login failed');
            throw new RuntimeException('No se pudo conectar al servidor de archivos');
        }

        @ftp_pasv($conn, $this->passive);

        if ($this->remotePath !== '' && @ftp_chdir($conn, $this->remotePath) === false) {
            @ftp_close($conn);
            error_log('MSL admin FTP: remote path unavailable');
            throw new RuntimeException('No se pudo acceder a la carpeta de tarifarios');
        }

        return $conn;
    }

    public function listFiles()
    {
        $conn = $this->connect();
        try {
            $list = @ftp_nlist($conn, '.');
            if ($list === false) {
                throw new RuntimeException('No se pudo listar los tarifarios');
            }

            $files = [];
            foreach ($list as $entry) {
                $name = basename($entry);
                if ($name === '.' || $name === '..') {
                    continue;
                }
                // nlist a veces devuelve dirs; intentar filtrar con size
                $size = @ftp_size($conn, $name);
                if ($size === -1) {
                    continue;
                }
                $files[] = $name;
            }

            natcasesort($files);
            return array_values($files);
        } finally {
            @ftp_close($conn);
        }
    }

    public function exists($filename)
    {
        $filename = self::sanitizeFilename($filename);
        $conn = $this->connect();
        try {
            $size = @ftp_size($conn, $filename);
            return $size !== -1;
        } finally {
            @ftp_close($conn);
        }
    }

    public function upload($localPath, $filename, $overwrite = false)
    {
        $filename = self::sanitizeFilename($filename);

        if (!is_file($localPath) || !is_readable($localPath)) {
            throw new RuntimeException('No se pudo leer el archivo temporal de subida');
        }

        $conn = $this->connect();
        try {
            $size = @ftp_size($conn, $filename);
            $exists = $size !== -1;

            if ($exists && !$overwrite) {
                throw new RuntimeException('FILE_EXISTS');
            }

            if ($exists && $overwrite) {
                @ftp_delete($conn, $filename);
            }

            $ok = @ftp_put($conn, $filename, $localPath, FTP_BINARY);
            if (!$ok) {
                throw new RuntimeException('No se pudo subir el archivo');
            }
        } finally {
            @ftp_close($conn);
        }
    }

    public function delete($filename)
    {
        $filename = self::sanitizeFilename($filename);
        $conn = $this->connect();
        try {
            $ok = @ftp_delete($conn, $filename);
            if (!$ok) {
                throw new RuntimeException('No se pudo eliminar el archivo');
            }
        } finally {
            @ftp_close($conn);
        }
    }
}
