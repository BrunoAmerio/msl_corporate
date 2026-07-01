<?php
header('Content-Type: application/json');

require_once __DIR__ . '/src/utils/EmailService.php';
require_once __DIR__ . '/src/utils/i18n.php';

// Validar que haya datos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Obtener idioma actual
$currentLang = getCurrentLanguage();

// Sanitizar y obtener los campos
$fullname = trim($_POST['fullname'] ?? '');
$message = trim($_POST['message'] ?? '');
$terms = isset($_POST['terms']) ? true : false;

if (!$fullname || !$message || !$terms) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Preparar datos para el servicio
$data = [
    'fullname' => $fullname,
    'message' => $message,
    'current_language' => $currentLang
];

// Enviar email usando el servicio
$emailService = new EmailService();
$result = $emailService->sendJobEmail($data);

// Responder
echo json_encode($result);

