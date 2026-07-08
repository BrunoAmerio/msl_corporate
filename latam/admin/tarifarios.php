<?php

require_once __DIR__ . '/src/bootstrap.php';
$authService->requireAuth();

$flashOk = null;
$flashError = null;
$pendingReplace = null;
$files = [];
$listError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if (!Csrf::validate($_POST['_csrf'] ?? null)) {
        $flashError = 'Token de seguridad inválido. Recargá la página e intentá de nuevo.';
    } else {
        try {
            if ($action === 'upload') {
                if (empty($_FILES['tarifario']) || !is_uploaded_file($_FILES['tarifario']['tmp_name'])) {
                    throw new RuntimeException('Seleccioná un archivo para subir');
                }
                $original = $_FILES['tarifario']['name'];
                $safeName = FtpTarifariosService::sanitizeFilename($original);
                $tmp = $_FILES['tarifario']['tmp_name'];

                try {
                    $ftpService->upload($tmp, $safeName, false);
                    $flashOk = 'Archivo subido correctamente: ' . $safeName;
                } catch (RuntimeException $e) {
                    if ($e->getMessage() === 'FILE_EXISTS') {
                        PendingUpload::clear();
                        $dest = PendingUpload::dir() . DIRECTORY_SEPARATOR . session_id() . '_' . hash('sha256', $safeName);
                        if (!@move_uploaded_file($tmp, $dest)) {
                            if (!@copy($tmp, $dest)) {
                                throw new RuntimeException('No se pudo preparar el archivo para reemplazo');
                            }
                        }
                        $token = bin2hex(random_bytes(16));
                        $_SESSION['pending_upload'] = [
                            'token' => $token,
                            'path' => $dest,
                            'name' => $safeName,
                            'expires' => time() + 600,
                        ];
                        $pendingReplace = $_SESSION['pending_upload'];
                    } else {
                        throw $e;
                    }
                }
            } elseif ($action === 'replace') {
                $pending = $_SESSION['pending_upload'] ?? null;
                $token = $_POST['pending_token'] ?? '';
                if (
                    !is_array($pending) ||
                    empty($pending['token']) ||
                    !hash_equals($pending['token'], (string) $token) ||
                    ($pending['expires'] ?? 0) < time() ||
                    empty($pending['path'])
                ) {
                    PendingUpload::clear();
                    throw new RuntimeException('La confirmación de reemplazo expiró. Volvé a subir el archivo.');
                }
                try {
                    $validatedPath = PendingUpload::resolveValidatedPath($pending['path']);
                } catch (RuntimeException $e) {
                    PendingUpload::clear();
                    throw $e;
                }
                $ftpService->upload($validatedPath, $pending['name'], true);
                $flashOk = 'Archivo reemplazado correctamente: ' . $pending['name'];
                PendingUpload::clear();
            } elseif ($action === 'cancel_replace') {
                PendingUpload::clear();
                $flashOk = 'Reemplazo cancelado';
            } elseif ($action === 'delete') {
                $name = FtpTarifariosService::sanitizeFilename((string) ($_POST['filename'] ?? ''));
                $ftpService->delete($name);
                $flashOk = 'Archivo eliminado: ' . $name;
            } else {
                $flashError = 'Acción no reconocida';
            }
        } catch (InvalidArgumentException $e) {
            $flashError = $e->getMessage();
        } catch (RuntimeException $e) {
            $flashError = $e->getMessage() === 'FILE_EXISTS'
                ? 'El archivo ya existe'
                : $e->getMessage();
        }
    }
}

if (!empty($_SESSION['pending_upload']) && empty($pendingReplace)) {
    $p = $_SESSION['pending_upload'];
    if (($p['expires'] ?? 0) >= time()) {
        try {
            PendingUpload::resolveValidatedPath($p['path'] ?? '');
            $pendingReplace = $p;
        } catch (RuntimeException $e) {
            PendingUpload::clear();
        }
    } else {
        PendingUpload::clear();
    }
}

try {
    $files = $ftpService->listFiles();
} catch (RuntimeException $e) {
    $listError = $e->getMessage();
}

$csrf = Csrf::token();
$publicBase = '../../tarifarios/';

?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MSL Admin — Tarifarios</title>
  <link rel="stylesheet" href="https://use.typekit.net/lex8tiv.css">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
  <main class="admin-wrap">
    <div class="admin-topbar">
      <div>
        <p class="admin-brand">MSL</p>
        <p class="admin-sub">Tarifarios</p>
      </div>
      <form method="post" action="logout.php">
        <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
        <button class="admin-btn admin-btn-secondary" type="submit">Cerrar sesión</button>
      </form>
    </div>

    <?php if ($flashOk): ?>
      <div class="admin-alert admin-alert-ok"><?php echo htmlspecialchars($flashOk, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if ($flashError): ?>
      <div class="admin-alert admin-alert-error"><?php echo htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <section class="admin-upload admin-card">
      <h2 style="margin-top:0;font-size:1.1rem;">Cargar tarifario</h2>
      <form method="post" action="tarifarios.php" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="action" value="upload">
        <input class="admin-input" type="file" name="tarifario" required>
        <button class="admin-btn" type="submit">Subir archivo</button>
      </form>
    </section>

    <section class="admin-card">
      <h2 style="margin-top:0;font-size:1.1rem;">Archivos cargados</h2>
      <?php if ($listError): ?>
        <p class="admin-alert admin-alert-error"><?php echo htmlspecialchars($listError, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php elseif (!$files): ?>
        <p class="admin-muted">No hay tarifarios cargados</p>
      <?php else: ?>
        <table class="admin-table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($files as $file): ?>
              <tr>
                <td>
                  <a href="<?php echo htmlspecialchars($publicBase . rawurlencode($file), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?>
                  </a>
                </td>
                <td>
                  <form method="post" action="tarifarios.php" class="js-delete-form" data-filename="<?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="filename" value="<?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="admin-btn admin-btn-danger" type="submit">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>

  <div class="admin-dialog-backdrop" id="delete-dialog" aria-hidden="true">
    <div class="admin-dialog" role="dialog" aria-modal="true" aria-labelledby="delete-title">
      <h2 id="delete-title">Eliminar archivo</h2>
      <p id="delete-message">Esta acción es irreversible.</p>
      <div class="admin-dialog-actions">
        <button type="button" class="admin-btn admin-btn-secondary" id="delete-cancel">Cancelar</button>
        <button type="button" class="admin-btn admin-btn-danger" id="delete-confirm">Eliminar</button>
      </div>
    </div>
  </div>

  <?php if ($pendingReplace): ?>
  <div class="admin-dialog-backdrop is-open" id="replace-dialog" aria-hidden="false">
    <div class="admin-dialog" role="dialog" aria-modal="true" aria-labelledby="replace-title">
      <h2 id="replace-title">Archivo existente</h2>
      <p>
        Ya existe un archivo con este nombre
        (<strong><?php echo htmlspecialchars($pendingReplace['name'], ENT_QUOTES, 'UTF-8'); ?></strong>).
        ¿Querés reemplazarlo? Esta acción es irreversible.
      </p>
      <div class="admin-dialog-actions">
        <form method="post" action="tarifarios.php">
          <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="action" value="cancel_replace">
          <button type="submit" class="admin-btn admin-btn-secondary">Cancelar</button>
        </form>
        <form method="post" action="tarifarios.php">
          <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="action" value="replace">
          <input type="hidden" name="pending_token" value="<?php echo htmlspecialchars($pendingReplace['token'], ENT_QUOTES, 'UTF-8'); ?>">
          <button type="submit" class="admin-btn admin-btn-danger">Reemplazar</button>
        </form>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <script>
    (function () {
      var dialog = document.getElementById('delete-dialog');
      var message = document.getElementById('delete-message');
      var cancelBtn = document.getElementById('delete-cancel');
      var confirmBtn = document.getElementById('delete-confirm');
      var pendingForm = null;

      document.querySelectorAll('.js-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
          event.preventDefault();
          pendingForm = form;
          var name = form.getAttribute('data-filename') || 'este archivo';
          message.textContent = 'Vas a eliminar ' + name + '. Esta acción es irreversible.';
          dialog.classList.add('is-open');
          dialog.setAttribute('aria-hidden', 'false');
        });
      });

      function closeDeleteDialog() {
        dialog.classList.remove('is-open');
        dialog.setAttribute('aria-hidden', 'true');
        pendingForm = null;
      }

      cancelBtn.addEventListener('click', closeDeleteDialog);
      confirmBtn.addEventListener('click', function () {
        if (pendingForm) {
          pendingForm.submit();
        }
      });
    })();
  </script>
</body>
</html>
