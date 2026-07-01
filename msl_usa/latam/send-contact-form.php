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
$name = trim($_POST['name'] ?? '');
$company = trim($_POST['company'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$country = trim($_POST['country'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$company || !$email || !$telephone || !$country || !$message) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Preparar datos para el servicio
$data = [
    'name' => $name,
    'company' => $company,
    'email' => $email,
    'telephone' => $telephone,
    'country' => $country,
    'message' => $message,
    'current_language' => $currentLang
];

// Enviar email usando el servicio
$emailService = new EmailService();
$result = $emailService->sendContactEmail($data);

// Responder
echo json_encode($result);
