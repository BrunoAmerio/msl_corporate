<?php
// Uso: php generate-password-hash.php 'TuPasswordSegura'
if ($argc < 2) {
    fwrite(STDERR, "Uso: php generate-password-hash.php 'password'\n");
    exit(1);
}
echo password_hash($argv[1], PASSWORD_DEFAULT) . PHP_EOL;
