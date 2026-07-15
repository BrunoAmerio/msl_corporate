<?php
require_once __DIR__ . '/src/utils/i18n.php';
$currentLang = getCurrentLanguage();
$translations = loadTranslations();
$seoPage = 'home';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
  <?php include 'src/components/php/gtm-head.php'; ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include 'src/components/php/seo-head.php'; ?>

  <link rel="stylesheet" href="src/styles/styles.css">
  <link rel="stylesheet" href="https://use.typekit.net/lex8tiv.css">
  <link rel="icon" type="image/x-icon" href="src/images/favicon.png">
</head>

<body>
  <?php include 'src/components/php/gtm-body.php'; ?>
  <?php include 'src/components/php/navbar.php'; ?>

  <main>
    <section id="home">
      <article>
        <video
          src="src/videos/video-home.mp4"
          poster=" "
          autoplay
          playsinline 
          loop
          muted
          preload="auto"
        >
          <p>Su navegador no soporta vídeos HTML5.</p>
        </video>

        <div class="text">
          <h4><?php echo t('home.hero.subtitle', $translations); ?></h4>
          <h1><?php echo t('home.hero.title', $translations); ?></h1>
          <p><?php echo t('home.hero.description', $translations); ?></p>
        </div>
      </article>

      <article class="webtools">
        <p class="webtools-title-mobile">WEBTOOLS <img src="src/images/arrow_down.svg" alt="arrow"/></p>
        <div class="element" onclick="window.open('https://www.mslwebtools.com/Schedule', '_blank')">
          <p>WEBTOOLS</p>
          <h2>Schedule</h2>
  
          <button>
            <p><?php echo t('home.common.view_more', $translations); ?></p>
            <img alt="arrow" src="src/images/arrow_blue.svg"/>
          </button>
        </div>
  
        <div class="element" onclick="window.open('https://www.mslwebtools.com/Schedule', '_blank')">
          <p>WEBTOOLS</p>
          <h2>Track & Trace</h2>
  
          <button>
            <p><?php echo t('home.common.view_more', $translations); ?></p>
            <img alt="arrow" src="src/images/arrow_blue.svg"/>
          </button>
        </div>

        <div class="element" onclick="window.open('https://www.mslwebtools.com/ExchangeRate', '_blank')">
          <p>WEBTOOLS</p>
          <h2>Exchange rate</h2>
  
          <button>
            <p><?php echo t('home.common.view_more', $translations); ?></p>
            <img alt="arrow" src="src/images/arrow_blue.svg"/>
          </button>
        </div>
      </article>
    </section>

    <section id="we_are">
      <article class="article-left">
        <h2><?php echo t('home.we_are.title', $translations); ?></h2>

        <p> <strong><?php echo t('home.we_are.description', $translations); ?></strong></p>

        <p><?php echo t('home.we_are.description_full', $translations); ?></p>

        <button onclick="window.location.href='<?php echo urlWithLang('about-us.php'); ?>'"><?php echo t('home.we_are.button', $translations); ?> <img src="src/images/arrow_blue.svg" alt="arrow"/></button>
      </article>

      <article class="article-right">
        <div class="text">
          <img alt="msl logo" src="src/images/header_logo.svg" class="msl-logo"/>
          <p><?php echo t('home.common.copyright', $translations); ?></p>
        </div>

        <img alt="we are MLS" src="src/images/we_are.png" class="img-right"/>
      </article>
    </section>

    <section id="services">
      <div class="services-top">
        <div>
          <p><?php echo t('home.services.title_prefix', $translations); ?></p>
          <h1><?php echo t('home.services.title', $translations); ?></h1>
        </div>

        <div>
          <div class="anchor" onclick="window.location.href='<?php echo urlWithLang('services.php'); ?>'">
            <a><?php echo t('home.services.button', $translations); ?> <img alt="arrow" src="src/images/arrow_blue.svg"/> </a>
          </div>

          <p><strong><?php echo t('home.services.subtitle', $translations); ?></strong></p>
          <p><?php echo t('home.services.subtitle_full', $translations); ?></p>
        </div>
      </div>

      <article>
        <div class="card" onclick="window.location.href='<?php echo urlWithLang('services.php#ocean'); ?>'">
          <video
            src="src/videos/video_ocean.mp4"
            poster=" "
            playsinline
            loop
            muted
            preload="metadata"
            class="hover-video"
          > 
            <p>Su navegador no soporta vídeos HTML5.</p>
          </video>
          <img src="src/images/ocean_preview.png" alt="image" class="preview-image"/>

          <div class="top">
            <div><img src="src/images/ocean_icon.svg"/></div>
            <p>01</p>
          </div>

          <div class="body">
            <h2><?php echo strtoupper(t('home.services.ocean', $translations)); ?></h2>

            <button onclick="window.location.href='<?php echo urlWithLang('services.php'); ?>'">
              <p><?php echo t('home.services.button_more', $translations); ?></p>
              <img alt="arrow" src="src/images/arrow_blue.svg"/>
            </button>
          </div>
        </div>

        <div class="card" onclick="window.location.href='<?php echo urlWithLang('services.php#air'); ?>'">
          <video
            src="src/videos/air (2).mp4"
            poster=" "
            playsinline
            loop
            muted
            preload="metadata"
            class="hover-video"
          > 
            <p>Su navegador no soporta vídeos HTML5.</p>
          </video>
          <img src="src/images/air_preview.png" alt="image" class="preview-image"/>

          <div class="top">
            <div><img src="src/images/air_icon.svg"/></div>
            <p>02</p>
          </div>

          <div class="body">
            <h2><?php echo strtoupper(t('home.services.air', $translations)); ?></h2>

            <button onclick="window.location.href='<?php echo urlWithLang('services.php'); ?>'">
              <p><?php echo t('home.services.button_more', $translations); ?></p>
              <img alt="arrow" src="src/images/arrow_blue.svg"/>
            </button>
          </div>
        </div>

        <div class="card" onclick="window.location.href='<?php echo urlWithLang('services.php#trucking'); ?>'">
          <video
            src="src/videos/Trucking Services.mp4"
            poster=" "
            playsinline
            loop
            muted
            preload="metadata"
            class="hover-video"
          > 
            <p>Su navegador no soporta vídeos HTML5.</p>
          </video>
          <img src="src/images/trucking_preview.png" alt="image" class="preview-image"/>
          <div class="top">
            <div><img src="src/images/trucking_icon.svg"/></div>
            <p>03</p>
          </div>

          <div class="body">
            <h2><?php echo strtoupper(t('home.services.truck', $translations)); ?></h2>

            <button onclick="window.location.href='<?php echo urlWithLang('services.php'); ?>'">
              <p><?php echo t('home.services.button_more', $translations); ?></p>
              <img alt="arrow" src="src/images/arrow_blue.svg"/>
            </button>
          </div>
        </div>
      </article>

      <article>
        <div class="card" onclick="window.location.href='<?php echo urlWithLang('services.php#warehouse'); ?>'">
          <video
            src="src/videos/Warehouse.mp4"
            poster=" "
            playsinline
            loop
            muted
            preload="metadata"
            class="hover-video"
          > 
            <p>Su navegador no soporta vídeos HTML5.</p>
          </video>
          <img src="src/images/warehousing_preview.png" alt="image" class="preview-image"/>
          <div class="top">
            <div><img src="src/images/warehousing_icon.svg"/></div>
            <p>04</p>
          </div>

          <div class="body">
            <h2><?php echo strtoupper(t('home.services.warehousing', $translations)); ?></h2>

            <button onclick="window.location.href='<?php echo urlWithLang('services.php'); ?>'">
              <p><?php echo t('home.services.button_more', $translations); ?></p>
              <img alt="arrow" src="src/images/arrow_blue.svg"/>
            </button>
          </div>
        </div>

        <div class="card" onclick="window.location.href='<?php echo urlWithLang('services.php#elogistics'); ?>'">
          <video
            src="src/videos/e-logistics.mp4"
            poster=" "
            playsinline
            loop
            muted
            preload="metadata"
            class="hover-video"
          > 
            <p>Su navegador no soporta vídeos HTML5.</p>
          </video>
          <img src="src/images/elogistic_preview.png" alt="image" class="preview-image"/>
          <div class="top">
            <div><img src="src/images/e_logistic_icon.svg"/></div>
            <p>05</p>
          </div>

          <div class="body">
            <h2><?php echo strtoupper(t('home.services.elogistics', $translations)); ?></h2>

            <button onclick="window.location.href='<?php echo urlWithLang('services.php'); ?>'">
              <p><?php echo t('home.services.button_more', $translations); ?></p>
              <img alt="arrow" src="src/images/arrow_blue.svg"/>
            </button>
          </div>
        </div>

        <div class="card" onclick="window.location.href='<?php echo urlWithLang('services.php#insurance'); ?>'">
          <video
            src="src/videos/Insurance video HOVER.mp4"
            poster=" "
            playsinline
            loop
            muted
            preload="metadata"
            class="hover-video"
          > 
            <p>Su navegador no soporta vídeos HTML5.</p>
          </video>
          <img src="src/images/insurance_preview.png" alt="image" class="preview-image"/>
          <div class="top">
            <div><img src="src/images/insurance_icon.svg"/></div>
            <p>06</p>
          </div>

          <div class="body">
            <h2 style='top: 20%;' ><?php echo strtoupper(t('home.services.insurance', $translations)); ?></h2>

            <button onclick="window.location.href='<?php echo urlWithLang('services.php'); ?>'">
              <p><?php echo t('home.services.button_more', $translations); ?></p>
              <img alt="arrow" src="src/images/arrow_blue.svg"/>
            </button>
          </div>
        </div>
      </article>
    </section>

    <section id="location">
      <div class="top">
        <div>
          <p><?php echo t('home.offices.title_prefix', $translations); ?></p>
          <h1><?php echo t('home.offices.title', $translations); ?></h1>
        </div>
      </div>

      <article>
        <img alt="map-background" src="src/images/location-background.svg" class="background"/>
        <div class="map">
          <img alt="map" src="src/images/map.svg"/>
          <div class="circle-container ar">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container bo">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container br">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container ch">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container co">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container ec">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container py">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container pe">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container uy">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>

          <div class="circle-container ve">
            <div class="circle large"></div>
            <div class="circle medium"></div>
            <div class="circle small"></div>
          </div>
        </div>

        <div class="display">
          <div class="text">
            <div class="text-container">
              <p><strong><?php echo t('home.offices.description_title', $translations); ?></strong></p>
            </div>

            <div class="chips-container">
              <button>
                Argentina
              </button>
  
              <button>
                Bolivia
              </button>
  
              <button>
                Brasil
              </button>
  
              <button>
                Chile
              </button>

              <button>
                Colombia
              </button>

              <button>
                Ecuador
              </button>

              <button>
                Paraguay
              </button>

              <button>
                Perú
              </button>

              <button>
                Uruguay
              </button>

              <button>
                Venezuela
              </button>
            </div>
          </div>

          <div class="location-preview">
            <div class="image-container">
              <img id="location-image"/>
            </div>
            <h4 id="location-title"></h4>
            <p id="location-text"></p>

            <button id="location-more-btn"><?php echo t('home.offices.button_more', $translations); ?></button>
          </div>
        </div>
      </article>
    </section>

    <section id="msl-group">
      <div class="top">
        <div class="left">
          <img src="src/images/we_are_msl.svg" alt="MSL Group"/>

          <a href="https://mslcorporate.com/" class="about_container" target="_blank">
            <p><?php echo t('home.msl_group.title', $translations); ?> <img src="src/images/arrow_blue.svg" alt="arrow"/></p>
          </a>
        </div>

        <div class="right">
          <p><?php echo t('home.msl_group.description', $translations); ?></p>
        </div>
      </div>

      <div class="bottom">
        <div class="number">
          <h3>+80</h3>
          <p><?php echo t('home.msl_group.offices', $translations); ?></p>
        </div>

        <div class="separation"></div>

        <div class="number">
          <h3>+30</h3>
          <p><?php echo t('home.msl_group.countries', $translations); ?></p>
        </div>

        <div class="separation"></div>
        
        <div class="number">
          <h3>+2000</h3>
          <p><?php echo t('home.msl_group.staff', $translations); ?></p>
        </div>
      </div>
    </section>

    <section id="logos">
      <div class="logos-container">
        <div class="logos-track">
          <img alt="" src="src/images/logos/asince.png"/>
          <img alt="" src="src/images/logos/msl.png"/>
          <img alt="" src="src/images/logos/sctrans.png"/>
          <img alt="" src="src/images/logos/stock_cargo.png"/>
          <img alt="" src="src/images/logos/tcc.svg"/>
          <img alt="" src="src/images/logos/cms.png"/>
          <img alt="" src="src/images/logos/mercure.png"/>
          <img alt="" src="src/images/logos/pier17.png"/>
          <img alt="" src="src/images/logos/oe.png"/>
          <img alt="" src="src/images/logos/odyssey.png"/>
          <!-- Duplicar los logos para el efecto infinito -->
          <img alt="" src="src/images/logos/asince.png"/>
          <img alt="" src="src/images/logos/msl.png"/>
          <img alt="" src="src/images/logos/sctrans.png"/>
          <img alt="" src="src/images/logos/stock_cargo.png"/>
          <img alt="" src="src/images/logos/tcc.svg"/>
          <img alt="" src="src/images/logos/cms.png"/>
          <img alt="" src="src/images/logos/mercure.png"/>
          <img alt="" src="src/images/logos/pier17.png"/>
          <img alt="" src="src/images/logos/oe.png"/>
          <img alt="" src="src/images/logos/odyssey.png"/>
        </div>
      </div>
    </section>

    <section id="contact-us">
      <article>
        <div class="left">
          <h1><?php echo t('home.cta.title', $translations); ?></h1>

          <button onclick="window.location.href='<?php echo urlWithLang('contact-us.php'); ?>'"><?php echo t('home.cta.button', $translations); ?></button>
        </div>
      </article>
    </section>

    <?php include 'src/components/php/footer.php'; ?>
  </main>

  <script src="src/scripts/chat.js"></script>
  <script src="src/scripts/mobile-menu.js"></script>
  <script src="src/scripts/number-animation.js"></script>
  <script src="src/scripts/video-hover.js"></script>
  <script>
    // Precargar videos de forma asíncrona después de que la página cargue
    document.addEventListener('DOMContentLoaded', () => {
      const videos = document.querySelectorAll('.hover-video');
      
      const preloadVideo = (video) => {
        const tempVideo = document.createElement('video');
        tempVideo.src = video.src;
        tempVideo.preload = 'auto';
        tempVideo.load();
      };

      // Precargar videos con retraso para no bloquear la carga inicial
      setTimeout(() => {
        if ('requestIdleCallback' in window) {
          const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                preloadVideo(entry.target);
                observer.unobserve(entry.target);
              }
            });
          }, { rootMargin: '50px' });

          videos.forEach(video => observer.observe(video));
        } else {
          videos.forEach(video => preloadVideo(video));
        }
      }, 100);
    });
  </script>
  <script>
    const chipsContainer = document.querySelector('.chips-container');
    const locationImage = document.getElementById('location-image');
    const locationTitle = document.getElementById('location-title');
    const locationText = document.getElementById('location-text');
    const locationMoreBtn = document.getElementById('location-more-btn');

    const countryData = {
      'Argentina': {
        image: 'src/images/countries/Argentina.jpg',
        title: 'Buenos Aires, Argentina',
        text: 'Av Belgrano 355 - Planta baja <br/>+54 11 5129-7000',
        slug: 'argentina'
      },

      'Bolivia': {
        image: 'src/images/countries/Bolivia.jpg',
        title: 'Santa Cruz de la Sierra, Bolivia',
        text: 'Av. Alemania 3er Anillo Calle Mapajo Nº 70<br/>(591) 3-3466777',
        slug: 'bolivia'
      },
      
      'Brasil': {
        image: 'src/images/countries/Brasil.jpg',
        title: 'São Paulo, Brasil',
        text: 'Rua da Consolação 331 - 8 Andar. Centro - São Paulo/SP CEP: 01301-000<br/>+55 11 2713-7900',
        slug: 'brazil'
      },
      
      'Chile': {
        image: 'src/images/countries/Chile.jpg',
        title: 'Santiago de Chile, Chile',
        text: 'Hendaya 60, Piso 10, Oficina 1001.<br/>56 229 794 800',
        slug: 'chile'
      },
      
      'Colombia': {
        image: 'src/images/countries/Colombia.jpg',
        title: 'Bogotá, Colombia',
        text: 'Calle 51 Nª 71/17/29 Normandía Primer Sector<br/>57 601 7481010',
        slug: 'colombia'
      },
      
      'Ecuador': {
        image: 'src/images/countries/Ecuador.jpg',
        title: 'Guayaquil, Ecuador',
        text: 'Av. Francisco de Orellana, World Trade Center, Torre B, Piso 11, Oficina 1107<br/>593 4 263-0102',
        slug: 'ecuador'
      },
      
      'Paraguay': {
        image: 'src/images/countries/Paraguay.jpg',
        title: 'Asunción, Paraguay',
        text: 'Avda. Aviadores del Chaco esq. Tte. 1ro. Carlos Rocholl - Edificio Kuarahy - 5to. Piso -<br/>Barrio Ykua Sati<br/>+5959 21 7286060 / 61 Mob: +595 992 441207',
        slug: 'paraguay'
      },
      
      'Perú': {
        image: 'src/images/countries/Peru.jpg',
        title: 'Lima, Perú',
        text: 'Av. Parque Gonzales Prada 379, Magdalena del Mar 15076 Lima<br/>511 462 2333',
        slug: 'peru'
      },
      
      'Uruguay': {
        image: 'src/images/countries/Uruguay.jpg',
        title: 'Montevideo, Uruguay',
        text: 'Rincón 391, Ciudad Vieja <br/>+598 2 9166566',
        slug: 'uruguay'
      },
      
      'Venezuela': {
        image: 'src/images/countries/Venezuela.jpg',
        title: 'Caracas, Venezuela',
        text: 'Av. Francisco de Miranda Edif. Parque Cristal Torre oeste piso 3 oficina top-3-3 Los Palos Grandes.<br/>Chacao. Caracas. Venezuela Zip code 1060<br/>+58 212 2853093 - 2855208 – 2853769',
        slug: 'venezuela'
      }
    };

    chipsContainer.addEventListener('click', (e) => {
      if (e.target.tagName === 'BUTTON') {
        const currentActive = chipsContainer.querySelector('.active');
        const locationPreview = document.querySelector('.location-preview');
        const mslGroupTop = document.querySelector('#msl-group .top');
        
        // Remover clase active de todos los circle-containers
        document.querySelectorAll('.circle-container').forEach(container => {
          container.classList.remove('active');
        });

        if (e.target === currentActive) {
          e.target.classList.remove('active');
          locationPreview.classList.remove('active');
          mslGroupTop.classList.remove('active');
        } else {
          if (currentActive) {
            currentActive.classList.remove('active');
          }
          e.target.classList.add('active');
          
          // Activar el circle-container correspondiente
          const countryClass = {
            'Argentina': 'ar',
            'Bolivia': 'bo',
            'Brasil': 'br',
            'Chile': 'ch',
            'Colombia': 'co',
            'Ecuador': 'ec',
            'Paraguay': 'py',
            'Perú': 'pe',
            'Uruguay': 'uy',
            'Venezuela': 've'
          };
          
          const country = e.target.textContent.trim();
          const circleContainer = document.querySelector(`.circle-container.${countryClass[country]}`);
          if (circleContainer) {
            circleContainer.classList.add('active');
          }

          const data = countryData[country];
          locationImage.src = data.image;
          locationTitle.textContent = data.title;
          locationText.innerHTML = data.text;
          locationMoreBtn.onclick = () => window.location.href = `offices#${data.slug}`;
          locationPreview.classList.add('active');
          mslGroupTop.classList.add('active');
        }
      }
    });

    // Añadir evento click a los circle-containers
    document.querySelectorAll('.circle-container').forEach(container => {
      container.addEventListener('click', (e) => {
        const currentActive = chipsContainer.querySelector('.active');
        const locationPreview = document.querySelector('.location-preview');
        const mslGroupTop = document.querySelector('#msl-group .top');
        
        // Remover clase active de todos los circle-containers
        document.querySelectorAll('.circle-container').forEach(cont => {
          cont.classList.remove('active');
        });

        // Mapeo inverso de clases a países
        const classToCountry = {
          'ar': 'Argentina',
          'bo': 'Bolivia',
          'br': 'Brasil',
          'ch': 'Chile',
          'co': 'Colombia',
          'ec': 'Ecuador',
          'py': 'Paraguay',
          'pe': 'Perú',
          'uy': 'Uruguay',
          've': 'Venezuela'
        };

        // Obtener la clase del circle-container (ar, bo, br, ch, co, ec, py, pe, uy, ve)
        const containerClass = Array.from(container.classList)
          .find(cls => classToCountry[cls]);
        
        const countryName = classToCountry[containerClass];
        const correspondingButton = Array.from(chipsContainer.querySelectorAll('button'))
          .find(button => button.textContent.trim() === countryName);

        if (currentActive) {
          currentActive.classList.remove('active');
        }

        container.classList.add('active');
        correspondingButton.classList.add('active');

        const data = countryData[countryName];
        locationImage.src = data.image;
        locationTitle.textContent = data.title;
        locationText.innerHTML = data.text;
        locationMoreBtn.onclick = () => window.location.href = `offices#${data.slug}`;
        locationPreview.classList.add('active');
        mslGroupTop.classList.add('active');
      });
    });

    // Inicializar Argentina como país por defecto
    const initializeArgentina = () => {
      const argentinaButton = Array.from(chipsContainer.querySelectorAll('button'))
        .find(button => button.textContent.trim() === 'Argentina');
      const argentinaCircle = document.querySelector('.circle-container.ar');
      const locationPreview = document.querySelector('.location-preview');
      const mslGroupTop = document.querySelector('#msl-group .top');
      
      if (argentinaButton && argentinaCircle) {
        argentinaButton.classList.add('active');
        argentinaCircle.classList.add('active');
        
        const data = countryData['Argentina'];
        locationImage.src = data.image;
        locationTitle.textContent = data.title;
        locationText.innerHTML = data.text;
        locationMoreBtn.onclick = () => window.location.href = `offices#${data.slug}`;
        locationPreview.classList.add('active');
        mslGroupTop.classList.add('active');
      }
    };

    // Ejecutar inicialización cuando el DOM esté listo
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializeArgentina);
    } else {
      initializeArgentina();
    }
  </script>
  <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/45020904.js"></script>
</body>
</html>