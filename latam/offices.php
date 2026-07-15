<?php
require_once __DIR__ . '/src/utils/i18n.php';
$currentLang = getCurrentLanguage();
$translations = loadTranslations();
$seoPage = 'offices';

$tarifariosDirectoryName = 'tarifarios';
$tarifariosDirectoryPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . $tarifariosDirectoryName;
$peruTarifarioFiles = [];

if (is_dir($tarifariosDirectoryPath) && is_readable($tarifariosDirectoryPath)) {
  $directoryEntries = scandir($tarifariosDirectoryPath);
  if (is_array($directoryEntries)) {
    foreach ($directoryEntries as $entry) {
      if ($entry === '.' || $entry === '..') {
        continue;
      }

      $entryPath = $tarifariosDirectoryPath . DIRECTORY_SEPARATOR . $entry;
      if (is_file($entryPath) && is_readable($entryPath)) {
        $peruTarifarioFiles[] = $entry;
      }
    }
  }
}

natcasesort($peruTarifarioFiles);
$peruTarifarioFiles = array_values($peruTarifarioFiles);

$peruTarifarioLinks = array_map(function ($file) use ($tarifariosDirectoryName) {
  return [
    'name' => $file,
    'url' => '../' . rawurlencode($tarifariosDirectoryName) . '/' . rawurlencode($file)
  ];
}, $peruTarifarioFiles);
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
  <style>
    .tarifario-modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
      z-index: 3000;
    }

    .tarifario-modal-overlay.is-open {
      display: flex;
    }

    .tarifario-modal {
      width: min(720px, 100%);
      max-height: 85vh;
      overflow-y: auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 18px 45px rgba(0, 0, 0, 0.3);
      padding: 24px;
    }

    .tarifario-modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 16px;
    }

    .tarifario-modal-header h4 {
      margin: 0;
      color: #112648;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .tarifario-modal-close {
      border: 0;
      background: transparent;
      cursor: pointer;
      font-size: 1.8rem;
      line-height: 1;
      color: #0f3f8c;
      padding: 0 6px;
    }

    .tarifario-modal-list {
      list-style: none;
      margin: 0;
      padding: 0;
      display: grid;
      gap: 10px;
    }

    .tarifario-modal-link {
      display: block;
      text-decoration: none;
      color: #112648;
      border: 1px solid #d5d9e3;
      border-radius: 10px;
      padding: 12px 14px;
      font-weight: 400;
      transition: background 0.2s ease, border-color 0.2s ease;
      word-break: break-word;
    }

    .tarifario-modal-link:hover {
      background: #f1f5ff;
      border-color: #0f3f8c;
    }
  </style>

</head>
<body>
    <?php include 'src/components/php/gtm-body.php'; ?>
    <?php include 'src/components/php/navbar.php'; ?>

  <main id="network-page">
    <section class="map-view">
      <div class="text">
        <p><?php echo t('offices_page.header.title_prefix', $translations); ?></p>
        <h1><?php echo t('offices_page.header.title', $translations); ?></h1>
      </div>

      <div class="bottom-text">
        <img alt="msl_logo" src="src/images/footer_logo.svg"/>
        <p><?php echo t('offices_page.header.copyright', $translations); ?></p>
      </div>


      <div class="images">
        <img alt="background" src="src/images/location-background.svg" class="background"/>
        <div class="country-map-container">
          <img alt="map" src="src/images/latam_map_points.svg" class="map"/>

          <div class="region-container active" id="north-america">
            <div class="chip-map" id="ar">
              <img alt="flag" src="src/images/flags/ar.svg"/>
              <p><?php echo t('offices_page.countries.ar.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="cl">
              <img alt="flag" src="src/images/flags/cl.svg"/>
              <p><?php echo t('offices_page.countries.cl.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="co">
              <img alt="flag" src="src/images/flags/co.svg"/>
              <p><?php echo t('offices_page.countries.co.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="bo">
              <img alt="flag" src="src/images/flags/bo.svg"/>
              <p><?php echo t('offices_page.countries.bo.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="uy">
              <img alt="flag" src="src/images/flags/uy.svg"/>
              <p><?php echo t('offices_page.countries.uy.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="ve">
              <img alt="flag" src="src/images/flags/ve.svg"/>
              <p><?php echo t('offices_page.countries.ve.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="pe">
              <img alt="flag" src="src/images/flags/pe.svg"/>
              <p><?php echo t('offices_page.countries.pe.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="ec">
              <img alt="flag" src="src/images/flags/ec.svg"/>
              <p><?php echo t('offices_page.countries.ec.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="py">
              <img alt="flag" src="src/images/flags/py.svg"/>
              <p><?php echo t('offices_page.countries.py.name', $translations); ?></p>
            </div>

            <div class="chip-map" id="br">
              <img alt="flag" src="src/images/flags/br.svg"/>
              <p><?php echo t('offices_page.countries.br.name', $translations); ?></p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="info-view">
      <!-- Información dinámica del país -->
      <div class="country-info">
        <div class="country-header">
          <div class="country-left">
            <h2 id="country-name"><?php echo t('offices_page.countries.ar.name', $translations); ?></h2>
            <p class="description" id="country-description"></p>
            <div class="services-section">
              <h3><?php echo t('offices_page.services_label', $translations); ?></h3>
              <div class="service-tags services-container">
                <!-- Los servicios se cargarán dinámicamente -->
              </div>
            </div>
            <p class="last-description" id="country-last-description"></p>
          </div>
          <div class="country-right">
            <img id="country-image" class="country-image" src="src/images/countries/Argentina.jpg" alt="<?php echo htmlspecialchars(t('offices_page.countries.ar.name', $translations)); ?>" loading="lazy"/>
          </div>
        </div>
      </div>

      <!-- Tarjetas de ubicaciones dinámicas -->
      <div class="locations-grid" id="locations-container">
        <!-- El contenido se llenará dinámicamente -->
      </div>
      <section class="tarifario-section" id="tarifario-section" aria-live="polite" style="display: none;">
        <!-- Contenido dinámico del tarifario -->
      </section>
      <div class="colombia-actions" id="colombia-actions" style="display: none;">
        <a class="data-policy-button" href="#" target="_blank" rel="noopener noreferrer">
          <span>Politica de protección de datos</span>
        </a>
      </div>
      <div class="tarifario-modal-overlay" id="tarifario-modal-overlay" aria-hidden="true">
        <div class="tarifario-modal" role="dialog" aria-modal="true" aria-labelledby="tarifario-modal-title">
          <div class="tarifario-modal-header">
            <h4 id="tarifario-modal-title">Tarifarios disponibles</h4>
            <button type="button" class="tarifario-modal-close" id="tarifario-modal-close" aria-label="Cerrar modal">&times;</button>
          </div>
          <ul class="tarifario-modal-list" id="tarifario-modal-list"></ul>
        </div>
      </div>
    </section>
  </main>

      <?php include 'src/components/php/footer.php'; ?>

  <script>
    // Variables globales
    let countriesData = null;
    let currentCountry = 'ar'; // Argentina por defecto
    const translations = <?php echo json_encode($translations); ?>;
    const currentLang = '<?php echo $currentLang; ?>';
    const peruTarifarioFiles = <?php echo json_encode($peruTarifarioLinks); ?>;
    const countryImages = {
      'ar': 'src/images/countries/Argentina.jpg',
      'bo': 'src/images/countries/Bolivia.jpg',
      'br': 'src/images/countries/Brasil.jpg',
      'cl': 'src/images/countries/Chile.jpg',
      'co': 'src/images/countries/Colombia.jpg',
      'ec': 'src/images/countries/Ecuador.jpg',
      'py': 'src/images/countries/Paraguay.jpg',
      'pe': 'src/images/countries/Peru.jpg',
      'uy': 'src/images/countries/Uruguay.jpg',
      've': 'src/images/countries/Venezuela.jpg'
    };
    let tarifarioSectionInitialized = false;

    // Función para cargar datos del JSON
    async function loadCountriesData() {
      try {
        const response = await fetch('src/data/data.json');
        countriesData = await response.json();
        return countriesData;
      } catch (error) {
        console.error('Error cargando datos de países:', error);
        return null;
      }
    }

    // Función para obtener traducción
    function getTranslation(key) {
      const keys = key.split('.');
      let value = translations;
      for (const k of keys) {
        if (value && value[k]) {
          value = value[k];
        } else {
          return key;
        }
      }
      return typeof value === 'string' ? value : key;
    }

    // Función para actualizar la información del país
    function updateCountryInfo(countryId) {
      if (!countriesData || !countriesData.countries[countryId]) return;

      const country = countriesData.countries[countryId];
      const countryTranslations = translations.offices_page?.countries?.[countryId];
      
      // Actualizar información del país
      const countryName = countryTranslations?.name || country.name;
      const countryDescription = countryTranslations?.description || country.description;
      const countryLastDescription = countryTranslations?.offices_description || country.lastDescription;
      const countryImageElement = document.getElementById('country-image');
      const fallbackImage = 'src/images/countries/Argentina.jpg';
      const imageSrc = countryImages[countryId] || fallbackImage;
      
      document.getElementById('country-name').textContent = countryName;
      document.getElementById('country-description').textContent = countryDescription;
      document.getElementById('country-last-description').textContent = countryLastDescription;
      if (countryImageElement) {
        countryImageElement.src = imageSrc;
        countryImageElement.alt = countryName ? `Imagen representativa de ${countryName}` : 'Imagen representativa de país';
      }

      // Actualizar servicios
      const servicesContainer = document.querySelector('.services-container');
      servicesContainer.innerHTML = '';
      
      const servicesFromTranslations = countryTranslations?.services && Object.keys(countryTranslations.services).length > 0
        ? Object.values(countryTranslations.services)
        : null;
      
      if (servicesFromTranslations && servicesFromTranslations.length > 0) {
        servicesFromTranslations.forEach(service => {
          const serviceTag = document.createElement('span');
          serviceTag.className = 'service-tag';
          // Agregar clase especial para E-Logistics para evitar salto de línea
          if (service === 'E-Logistics' || service.toLowerCase().includes('e-logistics') || service.toLowerCase().includes('elogistics')) {
            serviceTag.className += ' service-tag-elogistics';
          }
          serviceTag.textContent = service;
          servicesContainer.appendChild(serviceTag);
        });
      } else if (country.services && Array.isArray(country.services) && country.services.length > 0) {
        // Fallback a los servicios del data.json si no hay traducciones o están vacías
        country.services.forEach(service => {
          const serviceTag = document.createElement('span');
          serviceTag.className = 'service-tag';
          // Agregar clase especial para E-Logistics para evitar salto de línea
          if (service === 'E-Logistics' || service.toLowerCase().includes('e-logistics') || service.toLowerCase().includes('elogistics')) {
            serviceTag.className += ' service-tag-elogistics';
          }
          serviceTag.textContent = service;
          servicesContainer.appendChild(serviceTag);
        });
      }

      // Actualizar oficinas
      updateOffices(country.offices, countryId);

      // Gestionar tarifario para Perú
      updateTarifarioSection(countryId);
    }

    // Función para actualizar las oficinas
    function updateOffices(offices, countryId) {
      const locationsContainer = document.getElementById('locations-container');
      locationsContainer.innerHTML = '';

      offices.forEach((office, index) => {
        const locationCard = document.createElement('div');
        locationCard.className = `location-card ${office.expanded ? 'active' : ''}`;
        
        const countryName = translations.offices_page?.countries?.[countryId]?.name || countriesData.countries[countryId].name;
        const labels = translations.offices_page?.location_labels || {};
        
        locationCard.innerHTML = `
          <div class="location-header">
            <div class="location-left">
              <img alt="flag" src="src/images/flags/${countryId}.svg"/>
              <div class="location-text">
                <p>${countryName}</p>
                <h2>${office.name}</h2>
              </div>
            </div>
            <div class="location-right">
              <button class="toggle-btn">
                <img alt="arrow" src="src/images/arrow_blue.svg"/>
              </button>
            </div>
          </div>
          <div class="location-body" style="max-height: ${office.expanded ? 'auto' : '0'}; padding: ${office.expanded ? '20px' : '0 20px'};">
            <div class="info-row">
              <div class="info-item">
                <p>${labels.address || 'DIRECCIÓN'}</p>
                <p><strong>${office.address}</strong></p>
              </div>
              <div class="info-item">
                <p>${labels.warehouse || 'WAREHOUSE'}</p>
                <p><strong>${office.warehouse}</strong></p>
              </div>
            </div>
            <div class="info-row">
              <div class="info-item">
                <p>${labels.phone || 'PHONE'}</p>
                <p><strong>${office.phone}</strong></p>
              </div>
              <div class="info-item">
                <p>${labels.mail || 'MAIL'}</p>
                <p><strong>${office.email}</strong></p>
              </div>
            </div>
          </div>
        `;

        locationsContainer.appendChild(locationCard);
      });

      // Reconfigurar event listeners para las nuevas tarjetas
      setupLocationCards();
    }

    function buildTarifarioSection() {
      const section = document.getElementById('tarifario-section');
      if (!section) return;

      const labels = translations.offices_page?.tarifario || {};
      const title = labels.title || 'Tarifario de servicios';
      const bookLabel = labels.claims_book_label || 'Libro de reclamaciones';
      const viewTarifariosLabel = labels.view_tarifarios_label || 'Ver tarifarios';

      section.innerHTML = `
        <div class="tarifario-header">
          <h3>${title}</h3>
        </div>
        <div class="tarifario-actions">
          <a class="claims-book-button" href="http://190.81.174.115:7070/reclamaciones/" target="_blank" rel="noopener noreferrer">
            ${bookLabel}
          </a>
          <button type="button" class="claims-book-button" id="open-tarifario-modal">
            ${viewTarifariosLabel}
          </button>
        </div>
      `;

      setupPeruTarifarioModal();

      tarifarioSectionInitialized = true;
    }

    function setupPeruTarifarioModal() {
      const modalOverlay = document.getElementById('tarifario-modal-overlay');
      const modalList = document.getElementById('tarifario-modal-list');
      const openButton = document.getElementById('open-tarifario-modal');
      const closeButton = document.getElementById('tarifario-modal-close');
      if (!modalOverlay || !modalList || !openButton || !closeButton) return;

      modalList.innerHTML = '';
      if (!peruTarifarioFiles.length) {
        const emptyItem = document.createElement('li');
        emptyItem.textContent = 'No hay tarifarios disponibles.';
        modalList.appendChild(emptyItem);
      }

      peruTarifarioFiles.forEach(file => {
        const listItem = document.createElement('li');
        const link = document.createElement('a');
        link.className = 'tarifario-modal-link';
        link.href = file.url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        link.textContent = file.name;
        listItem.appendChild(link);
        modalList.appendChild(listItem);
      });

      openButton.addEventListener('click', () => {
        modalOverlay.classList.add('is-open');
        modalOverlay.setAttribute('aria-hidden', 'false');
      });

      closeButton.addEventListener('click', () => {
        modalOverlay.classList.remove('is-open');
        modalOverlay.setAttribute('aria-hidden', 'true');
      });

      modalOverlay.addEventListener('click', (event) => {
        if (event.target === modalOverlay) {
          modalOverlay.classList.remove('is-open');
          modalOverlay.setAttribute('aria-hidden', 'true');
        }
      });
    }

    function updateTarifarioSection(countryId) {
      const section = document.getElementById('tarifario-section');
      const colombiaActions = document.getElementById('colombia-actions');
      if (!section) return;

      if (countryId === 'pe') {
        if (!tarifarioSectionInitialized) {
          buildTarifarioSection();
        }
        section.style.display = 'block';
        if (colombiaActions) {
          colombiaActions.style.display = 'none';
        }
      } else {
        section.style.display = 'none';
        if (colombiaActions) {
          if (countryId === 'co') {
            setupColombiaActions();
            colombiaActions.style.display = 'flex';
          } else {
            colombiaActions.style.display = 'none';
          }
        }
      }
    }

    function setupColombiaActions() {
      const actionContainer = document.getElementById('colombia-actions');
      if (!actionContainer) return;

      const labels = translations.offices_page?.colombia_actions || {};
      const button = actionContainer.querySelector('.data-policy-button');
      if (!button) return;

      const url = labels.url || 'src/legales/POLITICA-DE-TRATAMIENTO-DE-DATOS-PERSONALES-MSL.pdf';
      const text = labels.label || 'Politica de protección de datos';

      button.setAttribute('href', url);
      button.querySelector('span').textContent = text;
    }

    // Función para configurar los event listeners de las tarjetas
    function setupLocationCards() {
      const locationCards = document.querySelectorAll('.location-card');

      locationCards.forEach(card => {
        const toggleBtn = card.querySelector('.location-header');
        const locationBody = card.querySelector('.location-body');
        
        toggleBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          
          const isCurrentlyActive = card.classList.contains('active');
          
          // Cerrar todas las tarjetas primero
          locationCards.forEach(otherCard => {
            otherCard.classList.remove('active');
            const otherBody = otherCard.querySelector('.location-body');
            otherBody.style.maxHeight = '0';
            otherBody.style.padding = '0 20px';
          });
          
          // Si la tarjeta actual no estaba activa, la activamos
          if (!isCurrentlyActive) {
            card.classList.add('active');
            locationBody.style.maxHeight = locationBody.scrollHeight + 'px';
            locationBody.style.padding = '20px';
          }
        });
      });
    }

    // Función para manejar el click en los chips del mapa
    function setupChipMaps() {
      const chipMaps = document.querySelectorAll('.chip-map');
      const infoView = document.querySelector('.info-view');

      chipMaps.forEach(chip => {
        chip.addEventListener('click', () => {
          const countryId = chip.id;
          currentCountry = countryId;
          updateCountryInfo(countryId);
          
          // Usar función auxiliar para scroll con offset del navbar
          scrollToElementWithOffset(infoView);
        });
      });
    }

    // Función para detectar hash en la URL y simular click
    function handleHashNavigation() {
      const hash = window.location.hash.substring(1);
      
      if (hash) {
        const countryMap = {
          'brazil': 'br',
          'brasil': 'br',
          'argentina': 'ar',
          'chile': 'cl',
          'colombia': 'co',
          'bolivia': 'bo',
          'uruguay': 'uy',
          'venezuela': 've',
          'peru': 'pe',
          'ecuador': 'ec',
          'paraguay': 'py'
        };
        
        const chipId = countryMap[hash.toLowerCase()];
        
        if (chipId) {
          setTimeout(() => {
            const chipElement = document.getElementById(chipId);
            if (chipElement) {
              chipElement.click();
            }
          }, 500);
        }
      }
    }

    // Función auxiliar para scroll con offset del navbar
    function scrollToElementWithOffset(element, offset = 120) {
      const elementPosition = element.offsetTop;
      const offsetPosition = elementPosition - offset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }

    // Función de inicialización
    async function init() {
      await loadCountriesData();
      
      if (countriesData) {
        // Configurar Argentina por defecto
        updateCountryInfo('ar');
        setupChipMaps();
        handleHashNavigation();
      }
    }

    // Ejecutar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', init);

    // Actualizar contenido cuando se cambia la oficina desde el navbar (mismo documento, solo cambia el hash)
    window.addEventListener('hashchange', handleHashNavigation);
  </script>
  <script src="src/scripts/mobile-menu.js"></script>
  <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/45020904.js"></script>
</body>
</html>