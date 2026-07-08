<?php

require_once __DIR__ . '/src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!Csrf::validate($_POST['_csrf'] ?? null)) {
    http_response_code(400);
    echo 'Token CSRF inválido';
    exit;
}

PendingUpload::clearSessionFiles();
$authService->logout();
header('Location: index.php');
exit;
