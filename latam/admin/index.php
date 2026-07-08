<?php

require_once __DIR__ . '/src/bootstrap.php';

if ($authService->isAuthenticated()) {
    header('Location: tarifarios.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $result = $authService->attempt($username, $password, client_ip());

    if ($result['ok']) {
        header('Location: tarifarios.php');
        exit;
    }

    if ($result['error'] === 'blocked') {
        $seconds = $rateLimiter->remainingBlockSeconds(client_ip());
        $minutes = max(1, (int) ceil($seconds / 60));
        $error = "Demasiados intentos fallidos. Probá de nuevo en aproximadamente {$minutes} minuto(s).";
    } else {
        $error = 'Credenciales inválidas';
    }
}

?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MSL Admin — Acceso</title>
  <link rel="stylesheet" href="https://use.typekit.net/lex8tiv.css">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
  <main class="admin-wrap">
    <p class="admin-brand">MSL</p>
    <p class="admin-sub">Administración de tarifarios</p>
    <div class="admin-card" style="max-width:420px;">
      <?php if ($error): ?>
        <div class="admin-alert admin-alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>
      <form method="post" action="index.php" autocomplete="off">
        <label class="admin-label" for="username">Usuario</label>
        <input class="admin-input" type="text" id="username" name="username" required>

        <label class="admin-label" for="password">Contraseña</label>
        <input class="admin-input" type="password" id="password" name="password" required>

        <button class="admin-btn" type="submit">Ingresar</button>
      </form>
    </div>
  </main>
</body>
</html>
