<?php
require_once __DIR__ . '/src/utils/i18n.php';
$currentLang = getCurrentLanguage();
$translations = loadTranslations();
$seoPage = 'contact-us';

$hubspotContactFormIds = [
  'en' => '08abadf8-861f-497f-b13c-5de05ef42443',
  'es' => 'ccc19224-5904-4821-99d2-d603e22f8193',
  'pt' => '3f8253cc-c5c2-45f3-a171-d55bd9bedc85',
];
$hubspotContactFormId = $hubspotContactFormIds[$currentLang] ?? $hubspotContactFormIds['es'];
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
  <?php include 'src/components/php/gtm-head.php'; ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include 'src/components/php/seo-head.php'; ?>

  <link rel="stylesheet" href="src/styles/styles.css?v=hubspot-embed">
  <link rel="stylesheet" href="https://use.typekit.net/lex8tiv.css">
  <link rel="icon" type="image/x-icon" href="src/images/favicon.png">
</head>

<body>
    <?php include 'src/components/php/gtm-body.php'; ?>
    <?php include 'src/components/php/navbar.php'; ?>

  <section id="contact-page">
    <article class="header">
      <div class="top"> 
        <h1><?php echo t('contact_page.header.title', $translations); ?></h1>
        <p><?php echo t('contact_page.header.description', $translations); ?></p>
      </div>
    </article>
  
    <article class="content">
      <div class="left">
        <img alt="contact" src="src/images/contact-image.png"/>
        <div class="branding">
        <div class="logo">
          <img src="src/images/header_logo.svg" alt="MSL Logo">
        </div>
        <p class="c ght"><?php echo t('contact_page.form.copyright', $translations); ?></p>
      </div>
      </div>

      <div class="right">
        <div class="left-information">
          <div>
            <div class="chip">
              <h4><?php echo t('contact_page.social_media.title', $translations); ?></h4>
            </div>
    
            <div class="body">
              <a href="https://www.linkedin.com/company/msl-corporate/" target="_blank" rel="noopener noreferrer">
                <img src="src/images/linkedin-svgrepo-com-2.svg" alt="LinkedIn"/>
              </a>
              <a href="https://www.instagram.com/mslcorporate" target="_blank" rel="noopener noreferrer">
                <img src="src/images/instagram-logo-facebook-2-svgrepo-com.svg" alt="Instagram"/>
              </a>
              <a href="https://www.facebook.com/mslcorporate" target="_blank" rel="noopener noreferrer">
                <img src="src/images/facebook-svgrepo-com.svg" alt="Facebook"/>
              </a>
            </div>
          </div>
        </div>
  
        <div class="contact-hubspot-form" id="hubspot-contact-form">
          <div
            class="hs-form-frame"
            data-region="na1"
            data-form-id="<?php echo htmlspecialchars($hubspotContactFormId, ENT_QUOTES, 'UTF-8'); ?>"
            data-portal-id="45020904"
          ></div>
        </div>
      </div>
    </article>
  </section>

  <section id="work-with-us">
    <div class="left-content">
      <div class="content">
        <h2><?php echo t('contact_page.work_with_us.title', $translations); ?></h2>
        <p class="subtitle"><?php echo t('contact_page.work_with_us.subtitle', $translations); ?></p>
        <p class="description"><?php echo t('contact_page.work_with_us.description', $translations); ?></p>
      </div>
    </div>
    
    <div class="right-content">
      <form id="work-form">
        <div class="form-group">
          <label for="fullname"><?php echo t('contact_page.work_with_us.form.fullname', $translations); ?></label>
          <input type="text" id="fullname" name="fullname" required>
        </div>
        
        <div class="form-group">
          <div class="cv-upload-row">
            <button type="button" class="file-upload-btn" onclick="document.getElementById('cv-file').click()">
              <img src="src/images/pdf_icon.svg" alt="PDF Icon" class="pdf-icon">
              <p><?php echo t('contact_page.work_with_us.form.attach_cv', $translations); ?></p>
            </button>
            <span class="cv-file-name" id="cv-file-name" aria-live="polite"></span>
          </div>
          <input type="file" id="cv-file" name="cv-file" accept=".pdf,.doc,.docx" style="display: none;" required>
        </div>
        
        <div class="form-group">
          <label for="message"><?php echo t('contact_page.work_with_us.form.message', $translations); ?></label>
          <textarea id="message" name="message" required></textarea>
        </div>
        
        <div class="checkbox-group">
          <input type="checkbox" id="terms" name="terms" required>
          <label for="terms"><?php echo t('contact_page.work_with_us.form.terms', $translations); ?></label>
        </div>
        
        <button type="submit" class="submit-btn"><?php echo t('contact_page.work_with_us.form.submit', $translations); ?></button>
      </form>
    </div>
  </section>

      <?php include 'src/components/php/footer.php'; ?>

  <script charset="utf-8" src="https://js.hsforms.net/forms/embed/45020904.js" defer></script>
  <script src="src/scripts/mobile-menu.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const currentLang = '<?php echo $currentLang; ?>';

      const cvFileInput = document.getElementById('cv-file');
      const cvFileNameEl = document.getElementById('cv-file-name');
      cvFileInput.addEventListener('change', () => {
        const file = cvFileInput.files[0];
        if (cvFileNameEl) {
          cvFileNameEl.textContent = file ? file.name : '';
        }
      });

      const workForm = document.getElementById('work-form');
      workForm.addEventListener('reset', () => {
        if (cvFileNameEl) cvFileNameEl.textContent = '';
      });
      workForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = new FormData(workForm);
        formData.append('current_language', currentLang);
        
        // Verificar que se haya seleccionado un archivo
        const cvFile = document.getElementById('cv-file').files[0];
        if (!cvFile) {
          alert('Por favor, adjunta tu CV');
          return;
        }

        fetch('send-work-form.php', {
          method: 'POST',
          body: formData
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              alert('Formulario de trabajo enviado correctamente');
              workForm.reset();
            } else {
              alert('Error enviando formulario: ' + data.message);
            }
          })
          .catch((error) => {
            console.error('Error:', error);
            alert('Error inesperado al enviar el formulario');
          });
      });
    });
  </script>
  <script type="text/javascript" id="hs-script-loader" async defer src="https://js.hs-scripts.com/45020904.js"></script>
</body>
</html>