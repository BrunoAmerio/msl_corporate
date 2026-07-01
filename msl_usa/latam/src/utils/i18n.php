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

/**
 * Genera una URL con el parámetro de idioma
 * @param string $url URL base (ej: "index.php", "contact-us.php")
 * @param string|null $lang Idioma específico (opcional, usa el actual si no se proporciona)
 * @param array $additionalParams Parámetros adicionales para la URL
 * @return string URL completa con el parámetro lang
 */
function urlWithLang($url, $lang = null, $additionalParams = []) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    // Si la URL contiene un hash, separarlo
    $hash = '';
    if (strpos($url, '#') !== false) {
        $parts = explode('#', $url, 2);
        $url = $parts[0];
        $hash = '#' . $parts[1];
    }
    
    // Parámetros base
    $params = array_merge(['lang' => $lang], $additionalParams);
    
    // Separar URL base de query string existente
    $urlParts = parse_url($url);
    $baseUrl = isset($urlParts['path']) ? $urlParts['path'] : $url;
    $existingQuery = isset($urlParts['query']) ? $urlParts['query'] : '';
    
    // Parsear query string existente
    parse_str($existingQuery, $existingParams);
    
    // Combinar parámetros (los nuevos sobrescriben los existentes)
    $allParams = array_merge($existingParams, $params);
    
    // Construir URL
    $queryString = http_build_query($allParams);
    $result = $baseUrl . ($queryString ? '?' . $queryString : '') . $hash;
    
    return $result;
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

