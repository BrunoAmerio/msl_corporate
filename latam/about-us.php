<?php
require_once __DIR__ . '/src/utils/i18n.php';
$currentLang = getCurrentLanguage();
$translations = loadTranslations();
$seoPage = 'about-us';
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
  
  <section id="about-us-home">
    <video
        src="src/videos/about_us.mp4"
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
      <p><?php echo t('about.header.title', $translations); ?></p>
      <h1><?php echo t('about.header.subtitle', $translations); ?></h1>
    </div>

    <div class="copyright">
      <img alt="logo" src="src/images/header_logo.svg"/>
      <p>
        <?php echo t('about.common.copyright', $translations); ?>
    </div>
  </section>

  <section id="about-us-main">
    <article class="who-we-are">
      <div class="chip">
        <p><?php echo t('about.who_we_are.chip', $translations); ?></p>
      </div>

      <div class="who-we-are-content">
        <div class="images-container">
          <div>
            <img src="src/images/about_us_image_1.png" alt="image"/>
          </div>
          <div>
            <img src="src/images/about_us_image_2.png" alt="image"/>
          </div>
        </div>

        <h1 class="reval"><?php echo t('about.who_we_are.description', $translations); ?></h1>
      </div>
    </article>

    <div class="separation"></div>

    <article class="our-history">
      <div class="top">
        <div class="chip">
          <p><?php echo t('about.our_history.chip', $translations); ?></p>
        </div>
      </div>

      <div class="content">
        <div class="left">
          <h4>
            <?php echo t('about.our_history.title', $translations); ?>
          </h4>

          <p>
            <?php echo t('about.our_history.description', $translations); ?>
            <br/> <br/>
            <?php echo t('about.our_history.description_continued', $translations); ?>
          </p>
        </div>

        <div class="right">
          <div>
            <img src="src/images/about_us_image_3.png" alt="image"/>
          </div>
          <div>
            <img src="src/images/about_us_image_4.png" alt="image"/>
          </div>
        </div>
      </div>
    </article>

    <article class="regional-reach">
      <div class="top">
        <div class="chip">
          <p><?php echo t('about.regional_reach.chip', $translations); ?></p>
        </div>
      </div>

      <div class="content">
        <div class="left">
          <h4>
            <?php echo t('about.regional_reach.title', $translations); ?>
          </h4>
        </div>

        <div class="right">
          <p><?php echo t('about.regional_reach.description', $translations); ?></p>
        </div>
      </div>

      <div class="numbers">
        <div class="number">
          <h3>+40</h3>
          <p><?php echo t('about.regional_reach.offices', $translations); ?></p>
        </div>

        <div class="divider"></div>

        <div class="number">
          <h3>+10</h3>
          <p><?php echo t('about.regional_reach.countries', $translations); ?></p>
        </div>

        <div class="divider"></div>
        
        <div class="number">
          <h3>+1000</h3>
          <p><?php echo t('about.regional_reach.staff', $translations); ?></p>
        </div>
      </div>
    </article>
  </section>  

      <?php include 'src/components/php/footer.php'; ?>

  <script src="src/scripts/mobile-menu.js"></script>
  <script src="src/scripts/typewriter.js"></script>
  <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/45020904.js"></script>
</body>
</html>