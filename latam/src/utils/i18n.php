<?php
/**
 * Sistema de internacionalización (i18n)
 * Maneja la detección de idioma, carga de traducciones y generación de URLs
 */

// Idiomas soportados
define('SUPPORTED_LANGUAGES', ['es', 'pt', 'en']);
define('DEFAULT_LANGUAGE', 'es');

/**
 * Obtiene el idioma actual desde la URL o parámetro GET
 * @return string Código del idioma (es, pt, en)
 */
function getCurrentLanguage() {
    $lang = isset($_GET['lang']) ? strtolower($_GET['lang']) : null;
    
    // Validar que el idioma esté soportado
    if ($lang && in_array($lang, SUPPORTED_LANGUAGES)) {
        return $lang;
    }
    
    return DEFAULT_LANGUAGE;
}

/**
 * Carga las traducciones del idioma especificado
 * @param string $lang Código del idioma
 * @return array|null Array con las traducciones o null si hay error
 */
function loadTranslations($lang = null) {
    if (!$lang) {
        $lang = getCurrentLanguage();
    }
    
    $translationsPath = __DIR__ . '/../translations/' . $lang . '.json';
    
    if (!file_exists($translationsPath)) {
        // Si no existe el archivo, intentar cargar el idioma por defecto
        if ($lang !== DEFAULT_LANGUAGE) {
            return loadTranslations(DEFAULT_LANGUAGE);
        }
        return [];
    }
    
    $translationsContent = file_get_contents($translationsPath);
    $translations = json_decode($translationsContent, true);
    
    return $translations ?: [];
}

/**
 * Obtiene una traducción específica
 * @param string $key Clave de la traducción (puede ser anidada con puntos, ej: "nav.home")
 * @param array $translations Array de traducciones (opcional, se carga automáticamente si no se proporciona)
 * @return string Texto traducido o la clave si no se encuentra
 */
function t($key, $translations = null) {
    if ($translations === null) {
        $translations = loadTranslations();
    }
    
    $keys = explode('.', $key);
    $value = $translations;
    
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return $key; // Retornar la clave si no se encuentra
        }
    }
    
    return is_string($value) ? $value : $key;
}

define('SEO_SITE_ORIGIN', 'https://mslcorporate.com');

/**
 * Convierte path de página pública a URL amigable (sin .php).
 * No altera send-*-form.php ni rutas admin.
 */
function seoPagePath($url) {
    $hash = '';
    if (strpos($url, '#') !== false) {
        $parts = explode('#', $url, 2);
        $url = $parts[0];
        $hash = '#' . $parts[1];
    }

    $path = $url;
    if (strpos($url, '?') !== false) {
        $path = strstr($url, '?', true);
    }

    $base = basename($path);
    $publicNames = ['index', 'about-us', 'services', 'offices', 'contact-us'];
    $name = preg_replace('/\.php$/', '', $base);

    if (!in_array($name, $publicNames, true)) {
        return $url . $hash;
    }

    if ($name === 'index') {
        $friendly = './';
    } else {
        $friendly = $name;
    }

    return $friendly . $hash;
}

/**
 * Genera una URL amigable con el parámetro de idioma (omite lang si es default).
 * @param string $url URL base (ej: "index.php", "contact-us.php")
 * @param string|null $lang Idioma específico (opcional, usa el actual si no se proporciona)
 * @param array $additionalParams Parámetros adicionales para la URL
 * @return string URL completa con el parámetro lang según convención SEO
 */
function urlWithLang($url, $lang = null, $additionalParams = []) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }

    $hash = '';
    if (strpos($url, '#') !== false) {
        $parts = explode('#', $url, 2);
        $url = $parts[0];
        $hash = '#' . $parts[1];
    }

    $urlParts = parse_url($url);
    $baseUrl = isset($urlParts['path']) ? $urlParts['path'] : $url;
    $existingQuery = isset($urlParts['query']) ? $urlParts['query'] : '';
    parse_str($existingQuery, $existingParams);

    $baseUrl = seoPagePath($baseUrl);

    $params = $additionalParams;
    if ($lang !== DEFAULT_LANGUAGE) {
        $params['lang'] = $lang;
    }
    $allParams = array_merge($existingParams, $params);
    if ($lang === DEFAULT_LANGUAGE) {
        unset($allParams['lang']);
    }

    $queryString = http_build_query($allParams);
    return $baseUrl . ($queryString ? '?' . $queryString : '') . $hash;
}

/**
 * @param string $pageKey home|about-us|services|offices|contact-us
 * @return string URL canónica absoluta
 */
function seoCanonicalUrl($pageKey, $lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }

    if ($pageKey === 'home' || $pageKey === 'index') {
        $path = '/latam/';
    } else {
        $path = '/latam/' . $pageKey;
    }

    $url = SEO_SITE_ORIGIN . $path;
    if ($lang !== DEFAULT_LANGUAGE) {
        $url .= '?lang=' . rawurlencode($lang);
    }
    return $url;
}

/**
 * Obtiene el código de idioma para mostrar en el selector
 * @return string Código del idioma en formato de display (ES, PT, ENG)
 */
function getLanguageDisplayCode() {
    $lang = getCurrentLanguage();
    $displayCodes = [
        'es' => 'ES',
        'pt' => 'PT',
        'en' => 'ENG'
    ];
    
    return $displayCodes[$lang] ?? 'ES';
}

