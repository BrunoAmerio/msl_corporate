<?php
// Incluir sistema de traducciones
require_once __DIR__ . '/../../utils/i18n.php';

// Configuración para determinar la página activa
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
if (empty($currentPage) || $currentPage === 'index') {
    $currentPage = 'index';
}

// Obtener idioma actual y traducciones
$currentLang = getCurrentLanguage();
$langDisplay = getLanguageDisplayCode();
$translations = loadTranslations();
?>
<header>
    <nav>
        <div class="logos-container">
            <img src="src/images/header_logo.svg" alt="Logo MSL" class="logo-msl" onclick="window.location.href='<?php echo urlWithLang('index.php'); ?>'"/>
        </div>

        <div class="buttons-container">
            <a href="<?php echo urlWithLang('index.php'); ?>" class="anchor-animation"><?php echo t('nav.home', $translations); ?></a>

            <a href="<?php echo urlWithLang('about-us.php'); ?>" class="anchor-animation"><?php echo t('nav.about', $translations); ?></a>

            <div class="dropdown">
                <a href="<?php echo urlWithLang('services.php'); ?>" class="anchor-animation"><?php echo t('nav.services', $translations); ?></a>
                <img alt="arrow" src="src/images/arrow_blue.svg"/>

                <div class="dropdown-content">
                    <a href="<?php echo urlWithLang('services.php#ocean'); ?>">
                        <?php echo t('services_page.ocean.title', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('services.php#air'); ?>">
                        <?php echo t('services_page.air.title', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('services.php#trucking'); ?>">
                        <?php echo t('services_page.truck.title', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('services.php#warehouse'); ?>">
                        <?php echo t('services_page.warehousing.title', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('services.php#elogistics'); ?>">
                        <?php echo t('services_page.elogistics.title', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('services.php#insurance'); ?>">
                        <?php echo t('services_page.insurance.title', $translations); ?>
                    </a>
                </div>
            </div>

            <div class="dropdown">
                <a href="<?php echo urlWithLang('offices.php'); ?>" class="anchor-animation"><?php echo t('nav.offices', $translations); ?></a>
                <img alt="arrow" src="src/images/arrow_blue.svg"/>

                <div class="dropdown-content">
                    <a href="<?php echo urlWithLang('offices.php#argentina'); ?>">
                        <?php echo t('offices_page.countries.ar.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#bolivia'); ?>">
                        <?php echo t('offices_page.countries.bo.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#brazil'); ?>">
                        <?php echo t('offices_page.countries.br.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#chile'); ?>">
                        <?php echo t('offices_page.countries.cl.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#colombia'); ?>">
                        <?php echo t('offices_page.countries.co.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#ecuador'); ?>">
                        <?php echo t('offices_page.countries.ec.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#paraguay'); ?>">
                        <?php echo t('offices_page.countries.py.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#peru'); ?>">
                        <?php echo t('offices_page.countries.pe.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#uruguay'); ?>">
                        <?php echo t('offices_page.countries.uy.name', $translations); ?>
                    </a>

                    <div class="separation"></div>

                    <a href="<?php echo urlWithLang('offices.php#venezuela'); ?>">
                        <?php echo t('offices_page.countries.ve.name', $translations); ?>
                    </a>
                </div>
            </div>

            <a href="<?php echo urlWithLang('contact-us.php'); ?>" class="anchor-animation"><?php echo t('nav.contact', $translations); ?></a>
        </div>

        <div class="other-container">
            <div class="social-media">
                <a href="https://www.linkedin.com/company/msl-corporate/" target="_blank"><img src="src/images/linkedin_logo.svg"/></a>
                <a href="https://www.instagram.com/mslcorporate" target="_blank"><img src="src/images/instagram_logo.svg"/></a>
                <a href="https://www.facebook.com/mslcorporate" target="_blank"><img src="src/images/facebook_logo.svg"/></a>
            </div>

            <div class="login-dropdown" id="loginDropdown">
                <a href="#" class="login" onclick="toggleLoginDropdown(event)">
                    <img alt="user" src="src/images/user_icon.svg"/>
                    <p><?php echo t('nav.login', $translations); ?></p>
                </a>
                <div class="login-dropdown-content" id="loginDropdownContent">
                    <div class="login-option" onclick="window.open('https://www.mslwebtools.com/Account/Login', '_blank')">
                        <span><?php echo t('nav.clients', $translations); ?></span>
                        <img src="src/images/arrow_outward.svg" alt="arrow outward"/>
                    </div>
                    <div class="divider"></div>
                    <div class="login-option" onclick="window.open('https://www.tracktraceagentes.com/', '_blank')">
                        <span><?php echo t('nav.agents', $translations); ?></span>
                        <img src="src/images/arrow_outward.svg" alt="arrow outward"/>
                    </div>
                </div>
            </div>

            <div class="language-selector" id="languageSelector">
                <div class="visible">
                    <span><?php echo $langDisplay; ?></span>
                    <img alt="arrow" src="src/images/arrow_light.svg" class="arrow"/>
                </div>

                <div class="dropdown-content">
                    <?php
                    $currentFile = basename($_SERVER['PHP_SELF']);
                    $langs = [
                        'es' => 'ES',
                        'pt' => 'PT',
                        'en' => 'ENG'
                    ];
                    foreach ($langs as $langCode => $langLabel):
                        if ($langCode !== $currentLang):
                    ?>
                    <a href="<?php echo urlWithLang($currentFile, $langCode); ?>"><?php echo $langLabel; ?></a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>

            <div class="region_selector" id="regionSelector">
                <p class="region_selector_title"><?php echo t('nav.region', $translations); ?></p>
                <div class="region_display" onclick="toggleRegionDropdown()">
                    <img src="src/images/world_light.svg" alt="world"/>
                    <p id="selectedRegion">LATAM</p>
                    <img src="src/images/arrow_light.svg" alt="arrow" class="arrow"/>
                </div>
                <div class="region_dropdown" id="regionDropdown">
                    <div class="region_option" onclick="selectRegion('USA')">
                        <img src="src/images/world_icon_blue.svg" alt="world"/>
                        <span>USA</span>
                    </div>
                    <div class="region_option" onclick="selectRegion('Nordic')">
                        <img src="src/images/world_icon_blue.svg" alt="world"/>
                        <span>Nordic</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="burger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="mobile-menu">
            <div class="mobile-menu-logo">
                <img src="src/images/footer_logo.svg" alt="Logo MSL" onclick="window.location.href='<?php echo urlWithLang('index.php'); ?>'"/>
            </div>
            <ul>
                <li><a href="<?php echo urlWithLang('index.php'); ?>"><?php echo t('nav.home', $translations); ?></a></li>

                <li class="separation"></li>

                <li><a href="<?php echo urlWithLang('about-us.php'); ?>"><?php echo t('nav.about', $translations); ?></a></li>

                <li class="separation"></li>

                <li id="solutions-dropdown">
                    <div class="dropdown">
                        <p onclick="toggleDropdown('solutions-dropdown')">
                            <?php echo t('nav.solutions', $translations); ?>
                            <img alt="arrow" src="src/images/arrow_blue.svg"/>
                        </p>

                        <div class="dropdown-content">
                            <a href="<?php echo urlWithLang('services.php#ocean'); ?>"><?php echo t('services_page.ocean.title', $translations); ?></a>
            
                            <a href="<?php echo urlWithLang('services.php#air'); ?>"><?php echo t('services_page.air.title', $translations); ?></a>
            
                            <a href="<?php echo urlWithLang('services.php#warehouse'); ?>"><?php echo t('services_page.warehousing.title', $translations); ?></a>
            
                            <a href="<?php echo urlWithLang('services.php#elogistics'); ?>"><?php echo t('services_page.elogistics.title', $translations); ?></a>
            
                            <a href="<?php echo urlWithLang('services.php#insurance'); ?>"><?php echo t('services_page.insurance.title', $translations); ?></a>
            
                            <a href="<?php echo urlWithLang('services.php#trucking'); ?>"><?php echo t('services_page.truck.title', $translations); ?></a>
                        </div>
                    </div>
                </li>

                <li class="separation"></li>

                <li id="offices-dropdown">
                    <div class="dropdown">
                        <p onclick="toggleDropdown('offices-dropdown')">
                            <?php echo t('nav.offices', $translations); ?>
                            <img alt="arrow" src="src/images/arrow_blue.svg"/>
                        </p>

                        <div class="dropdown-content">
                            <a href="<?php echo urlWithLang('offices.php#argentina'); ?>"><?php echo t('offices_page.countries.ar.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#brazil'); ?>"><?php echo t('offices_page.countries.br.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#chile'); ?>"><?php echo t('offices_page.countries.cl.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#colombia'); ?>"><?php echo t('offices_page.countries.co.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#ecuador'); ?>"><?php echo t('offices_page.countries.ec.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#peru'); ?>"><?php echo t('offices_page.countries.pe.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#uruguay'); ?>"><?php echo t('offices_page.countries.uy.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#venezuela'); ?>"><?php echo t('offices_page.countries.ve.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#bolivia'); ?>"><?php echo t('offices_page.countries.bo.name', $translations); ?></a>
                            <a href="<?php echo urlWithLang('offices.php#paraguay'); ?>"><?php echo t('offices_page.countries.py.name', $translations); ?></a>
                        </div>
                    </div>
                </li>

                <li class="separation"></li>
                <li><a href="<?php echo urlWithLang('contact-us.php'); ?>"><?php echo t('nav.contact', $translations); ?></a></li>
                <li class="separation"></li>

            </ul>
            <div class="bottom-container">
                <div class="top-container"> 
                    <button onclick="window.open('https://www.ifswebtools.com/Account/LogIn', '_blank')" class="login-button">
                        <?php echo t('nav.login', $translations); ?>
                    </button>

                    <div class="separation"></div>

                    <div class="language-selector">
                        <div class="visible">
                            <div><?php echo $langDisplay; ?></div>
                            <img alt="arrow" src="src/images/arrow_blue.svg"/>
                        </div>

                        <div class="dropdown-content">
                            <?php
                            $currentFile = basename($_SERVER['PHP_SELF']);
                            $langs = [
                                'es' => 'ES',
                                'pt' => 'PT',
                                'en' => 'ENG'
                            ];
                            foreach ($langs as $langCode => $langLabel):
                                if ($langCode !== $currentLang):
                            ?>
                            <a href="<?php echo urlWithLang($currentFile, $langCode); ?>"><?php echo $langLabel; ?></a>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="social-media">
                    <p><?php echo t('nav.social_media', $translations); ?>:</p>
                        <a href="https://www.linkedin.com/company/msl-corporate/" target="_blank"><img src="src/images/linkedin_logo.svg"/></a>
                        <a href="https://www.instagram.com/mslcorporate" target="_blank"><img src="src/images/instagram_logo.svg"/></a>
                        <a href="https://www.facebook.com/mslcorporate" target="_blank"><img src="src/images/facebook_logo.svg"/></a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="src/scripts/lang-preserver.js"></script>