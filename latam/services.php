<?php
require_once __DIR__ . '/src/utils/i18n.php';
$currentLang = getCurrentLanguage();
$translations = loadTranslations();
$seoPage = 'services';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
  <?php include 'src/components/php/gtm-head.php'; ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include 'src/components/php/seo-head.php'; ?>

  <link rel="stylesheet" href="src/styles/styles.css?v=final">
  <link rel="stylesheet" href="https://use.typekit.net/lex8tiv.css">
  <link rel="icon" type="image/x-icon" href="src/images/favicon.png">
</head>
<body>
    <?php include 'src/components/php/gtm-body.php'; ?>
    <?php include 'src/components/php/navbar.php'; ?>
  
  <section id="services-page-top">
    <img alt="background" src="src/images/servicios.png" class="background"/>

    <div class="top">
      <h1><?php echo t('services_page.header.title', $translations); ?></h1>
      <p><?php echo t('services_page.header.description', $translations); ?></p>
    </div>

    <div class="slider-container">
      <div class="slider">
        <p><?php echo t('services_page.slider.text', $translations); ?></p>
        <p><?php echo t('services_page.slider.text', $translations); ?></p>
        <p><?php echo t('services_page.slider.text', $translations); ?></p>
      </div>
    </div>
  </section>

  <section id="services-container-info">
    <div class="top">
      <h2><?php echo t('services_page.info.title', $translations); ?></h2>
    </div>
    <div class="middle">
      <img src="src/images/nlo_logo.svg">
      <div class="separation"></div>
      <p><?php echo t('services_page.info.nlo_description', $translations); ?></p>
    </div>
    <div class="bottom">
      <img src="src/images/parallax_nlo.png" class="parallax-image">
    </div>
  </section>

  <section id="services-container">
    <div class="service ocean-service">
     <div class="image">
      <img src="src/images/ocean_image_service.png" alt="image"/>
     </div>

     <div class="content">
      <div class="chip">
        <img src="src/images/ocean_icon.svg"/>
      </div>

      <h2><?php echo t('services_page.ocean.title', $translations); ?></h2>
      <p class="paragraph">
        <?php echo t('services_page.ocean.description', $translations); ?>
      </p>

      <div class="options">
        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.ocean.lcl', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.ocean.fcl', $translations); ?></strong></p>
        </div>
        <button><?php echo t('services_page.air.button', $translations); ?></button>
      </div>
     </div>
    </div>

    <div class="service air-service">
      <div class="image">
        <img src="src/images/air_image_service.png" alt="image"/>
      </div>
 
      <div class="content">
       <div class="chip">
         <img src="src/images/air_icon.svg"/>
       </div>
 
       <h2><?php echo t('services_page.air.title', $translations); ?></h2>
       <p class="paragraph"><?php echo t('services_page.air.description', $translations); ?></p>
 
       <div class="options">
        <p class="extra-paragraph">
          <?php echo t('services_page.air.full_description', $translations); ?>
        </p>
        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.air.consolidated', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.air.back_to_back', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.air.charters', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.air.multimodal', $translations); ?></strong></p>
        </div>

        <button><?php echo t('services_page.air.button', $translations); ?></button>
       </div>
      </div>
    </div>

    <div class="service trucking-service">
      <div class="image">
        <img src="src/images/trucking_image_service.png" alt="image"/>
      </div>
 
      <div class="content">
       <div class="chip">
         <img src="src/images/trucking_icon.svg"/>
       </div>
 
       <h2><?php echo t('services_page.truck.title', $translations); ?></h2>
       <p class="paragraph"><?php echo t('services_page.truck.description', $translations); ?></p>
 
       <div class="options">
         <p class="extra-paragraph">
          <?php echo t('services_page.truck.full_description', $translations); ?>
        </p>
        <button><?php echo t('services_page.truck.button', $translations); ?></button>
       </div>
      </div>
    </div>

    <div class="service warehouse-service">
      <div class="image">
        <img src="src/images/warehousing_image_service.png" alt="image"/>
      </div>
 
      <div class="content">
       <div class="chip">
         <img src="src/images/warehousing_icon.svg"/>
       </div>
 
       <h2><?php echo t('services_page.warehousing.title', $translations); ?></h2>
       <p class="paragraph"><?php echo t('services_page.warehousing.description', $translations); ?></p>
 
       <div class="options">
         <p class="extra-paragraph">
          <?php echo t('services_page.warehousing.full_description', $translations); ?>
        </p>
        <button><?php echo t('services_page.warehousing.button', $translations); ?></button>
       </div>
      </div>
    </div>

    <div class="service elogistics-service">
      <div class="image">
        <img src="src/images/elogistic_image_service.png" alt="image"/>
      </div>
 
      <div class="content">
       <div class="chip">
         <img src="src/images/e_logistic_icon.svg"/>
       </div>
 
       <h2><?php echo t('services_page.elogistics.title', $translations); ?></h2>
       <p class="paragraph"><?php echo t('services_page.elogistics.description', $translations); ?></p>
 
       <div class="options">
        <p class="extra-paragraph">
          <?php echo t('services_page.elogistics.full_description', $translations); ?>
        </p>
        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.elogistics.po_box', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.elogistics.fulfillment', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.elogistics.air_sea_freight', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.elogistics.software', $translations); ?></strong></p>
        </div>

        <div class="option">
          <div class="circle"></div>
          <p><strong><?php echo t('services_page.elogistics.b2c_solutions', $translations); ?></strong></p>
        </div>

         
        <button><?php echo t('services_page.elogistics.button', $translations); ?></button>
       </div>
      </div>
    </div>

    <div class="service insurance-service">
      <div class="image">
        <img src="src/images/insurance_image_service.png" alt="image"/>
      </div>
 
      <div class="content">
       <div class="chip">
         <img src="src/images/insurance_icon.svg"/>
       </div>
 
       <h2><?php echo t('services_page.insurance.title', $translations); ?></h2>
       <p class="paragraph"><?php echo t('services_page.insurance.description', $translations); ?></p>
 
       <div class="options">
         <p class="extra-paragraph">
          <?php echo t('services_page.insurance.full_description', $translations); ?>
        </p>
        <button><?php echo t('services_page.insurance.button', $translations); ?></button>
       </div>
      </div>
    </div>
  </section>

      <?php include 'src/components/php/footer.php'; ?>

  <script src="src/scripts/mobile-menu.js"></script>
  <script src="src/scripts/parallax.js"></script>
  <script>
    // Función para manejar el scroll con hash de servicios
    function handleHashScroll() {
      if(window.location.hash) {
        const hash = window.location.hash.substring(1);
        
        const serviceMap = {
          'maritimo': 'ocean-service',
          'maritime': 'ocean-service',
          'ocean': 'ocean-service',
          'aereo': 'air-service',
          'air': 'air-service',
          'terrestre': 'trucking-service',
          'land': 'trucking-service',
          'trucking': 'trucking-service',
          'warehouse': 'warehouse-service',
          'warehousing': 'warehouse-service',
          'almacenamiento': 'warehouse-service',
          'elogistics': 'elogistics-service',
          'e-logistics': 'elogistics-service',
          'ecommerce': 'elogistics-service',
          'comercio-electronico': 'elogistics-service',
          'insurance': 'insurance-service',
          'seguro': 'insurance-service',
          'seguros': 'insurance-service'
        };
        
        const serviceClass = serviceMap[hash.toLowerCase()];
        
        if (serviceClass) {
          setTimeout(() => {
            const element = document.querySelector(`.${serviceClass}`);
            if(element) {
              element.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
              });
            }
          }, 500);
        } else {
          // Si no hay mapeo, intentar buscar por clase directa
          const element = document.querySelector(`.${hash.toLowerCase()}-service`);
          if(element) {
            element.scrollIntoView({ 
              behavior: 'smooth', 
              block: 'center' 
            });
          }
        }
      }
    }

    // Escuchar cambios en el hash
    window.addEventListener('hashchange', handleHashScroll);

    // Ejecutar al cargar la página si hay hash
    window.addEventListener('load', handleHashScroll);
  </script>
  <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/45020904.js"></script>
</body>
</html>