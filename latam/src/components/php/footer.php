<?php
// Incluir sistema de traducciones
require_once __DIR__ . '/../../utils/i18n.php';

// Obtener traducciones
$translations = loadTranslations();
?>
<footer>
    <div class="top">
        <div class="fist-column">
            <img src="src/images/footer_logo.svg" alt="Logo MSL" class="msl_logo"/>
            <div class="separation"></div>

            <div class="region_selector" id="regionSelectorFooter">
                <p class="region_selector_title"><?php echo t('footer.region', $translations); ?></p>
                <div class="region_display" onclick="toggleRegionDropdownFooter()">
                    <img src="src/images/world_icon_blue.svg" alt="" aria-hidden="true"/>
                    <p id="selectedRegionFooter">LATAM</p>
                    <img src="src/images/arrow_blue.svg" alt="" aria-hidden="true" class="arrow"/>
                </div>
                <div class="region_dropdown" id="regionDropdownFooter">
                    <div class="region_option" onclick="selectRegionFooter('USA')">
                        <img src="src/images/world_icon_blue.svg" alt="" aria-hidden="true"/>
                        <span>USA</span>
                    </div>
                    <div class="region_option" onclick="selectRegionFooter('Nordic')">
                        <img src="src/images/world_icon_blue.svg" alt="" aria-hidden="true"/>
                        <span>Nordic</span>
                    </div>
                </div>
            </div>
        </div>
  
        <div class="second-column">
            <h4><?php echo t('footer.sections', $translations); ?></h4>
            <a href="<?php echo urlWithLang('index.php'); ?>"><?php echo t('nav.home', $translations); ?></a>
            <a href="<?php echo urlWithLang('about-us.php'); ?>"><?php echo t('nav.about', $translations); ?></a>
            <a href="<?php echo urlWithLang('services.php'); ?>"><?php echo t('nav.services', $translations); ?></a>
            <a class="special-anchor" href="<?php echo urlWithLang('offices.php'); ?>"><?php echo t('nav.offices', $translations); ?></a>
            <a href="<?php echo urlWithLang('contact-us.php'); ?>"><?php echo t('nav.contact', $translations); ?></a>
        </div>

        <div class="third-column">
            <h4><?php echo t('footer.social_media', $translations); ?></h4>
            <img src="src/images/linkedin-svgrepo-com-2.svg" alt="LinkedIn" onclick="window.open('https://www.linkedin.com/company/msl-corporate/', '_blank')"/>
            <img src="src/images/instagram-logo-facebook-2-svgrepo-com.svg" alt="Instagram" onclick="window.open('https://www.instagram.com/mslcorporate', '_blank')"/>
            <img src="src/images/facebook-svgrepo-com.svg" alt="Facebook" onclick="window.open('https://www.facebook.com/mslcorporate', '_blank')"/>
        </div>

        <div class="fourth-column">
            <div class="nlo_logo">
                <img src="src/images/nlo_logo.svg" alt="NLO">
                <p><?php echo t('footer.nlo_description', $translations); ?></p>
            </div>

            <div class="separation"></div>

            <div class="icargo_logo">
                <a href="https://www.icargoalliance.com/" target="_blank" rel="noopener noreferrer">
                    <img src="src/images/icargo_logo.png" alt="iCargo">
                </a>
                <p><?php echo t('footer.icargo_description', $translations); ?></p>
            </div>
        </div>
    </div>

    <!-- Footer mobile -->
    <div class="mobile top">
        <div class="logo-and-region-container">
            <img src="src/images/footer_logo.svg" alt="Logo MSL" class="logo"/>
    
            <div class="region_selector_mobile">
                <p class="region_selector_title"><?php echo t('footer.region', $translations); ?></p>
                <div class="region_display" onclick="toggleRegionDropdownMobileFooter()">
                    <img src="src/images/world_icon_blue.svg" alt="" aria-hidden="true"/>
                    <p id="selectedRegionMobileFooter">LATAM</p>
                    <img src="src/images/arrow_blue.svg" alt="" aria-hidden="true" class="arrow"/>
                </div>
                <div class="region_dropdown" id="regionDropdownMobileFooter">
                    <div class="region_option" onclick="selectRegionMobileFooter('USA')">
                        <img src="src/images/world_icon_blue.svg" alt="" aria-hidden="true"/>
                        <span>USA</span>
                    </div>
                    <div class="region_option" onclick="selectRegionMobileFooter('Nordic')">
                        <img src="src/images/world_icon_blue.svg" alt="" aria-hidden="true"/>
                        <span>Nordic</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-container">
        <a href="<?php echo urlWithLang('index.php'); ?>"><?php echo t('nav.home', $translations); ?></a>
            <a href="<?php echo urlWithLang('about-us.php'); ?>"><?php echo t('nav.about', $translations); ?></a>
            <a href="<?php echo urlWithLang('services.php'); ?>"><?php echo t('nav.services', $translations); ?></a>
            <a class="special-anchor" href="<?php echo urlWithLang('offices.php'); ?>"><?php echo t('nav.offices', $translations); ?></a>
            <a href="<?php echo urlWithLang('contact-us.php'); ?>"><?php echo t('nav.contact', $translations); ?></a>
        </div>

        <!-- Logos NLO e iCargo para mobile -->
        <div class="logos-section">
            <div class="nlo_logo">
                <img src="src/images/nlo_logo.svg" alt="NLO">
                <p><?php echo t('footer.nlo_description', $translations); ?></p>
            </div>

            <div class="icargo_logo">
                <a href="https://www.icargoalliance.com/" target="_blank" rel="noopener noreferrer">
                    <img src="src/images/icargo_logo.png" alt="iCargo">
                </a>
                <p><?php echo t('footer.icargo_description', $translations); ?></p>
            </div>
        </div>

        <div class="footer-bottom">    
            <div class="third-column">
                <h4><?php echo t('footer.social_media', $translations); ?></h4>
                <img src="src/images/linkedin-svgrepo-com-2.svg" alt="LinkedIn" onclick="window.open('https://www.linkedin.com/company/msl-corporate/', '_blank')"/>
                <img src="src/images/instagram-logo-facebook-2-svgrepo-com.svg" alt="Instagram" onclick="window.open('https://www.instagram.com/mslcorporate', '_blank')"/>
                <img src="src/images/facebook-svgrepo-com.svg" alt="Facebook" onclick="window.open('https://www.facebook.com/mslcorporate', '_blank')"/>
            </div>
        </div>
    </div>

    <div class="bottom">
        <div class="second-top">
            <div class="msl_logo">
                <img src="src/images/footer_logo_2.svg" alt="Logo MSL"/>
            </div>
            <a href="src/legales/terms-and-conditions.pdf" target="_blank"><?php echo t('footer.terms_and_conditions', $translations); ?></a>
            <p><?php echo t('footer.made_by', $translations); ?> <a href="https://rangocreative.com/" target="_blank">RangoStudio™</a></p>
        </div>
        <div class="separation"></div>
        <div class="second-bottom">
            <p><?php echo t('footer.copyright', $translations); ?></p>
        </div>
    </div>
</footer>

<script src="src/scripts/region-selector.js"></script>
