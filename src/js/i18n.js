/**
 * Sistema de internacionalización (i18n) para index.html
 * Maneja la carga de traducciones y aplicación de textos
 */

const SUPPORTED_LANGUAGES = ['es', 'pt', 'en', 'sv', 'no'];
const DEFAULT_LANGUAGE = 'en';

let currentLanguage = DEFAULT_LANGUAGE;
let translations = {};

/**
 * Obtiene el idioma actual desde localStorage o parámetro URL
 * @returns {string} Código del idioma (es, pt, en)
 */
function getCurrentLanguage() {
  // Verificar parámetro URL
  const urlParams = new URLSearchParams(window.location.search);
  const langParam = urlParams.get('lang');
  
  if (langParam && SUPPORTED_LANGUAGES.includes(langParam.toLowerCase())) {
    return langParam.toLowerCase();
  }
  
  // Verificar localStorage
  const storedLang = localStorage.getItem('msl_language');
  if (storedLang && SUPPORTED_LANGUAGES.includes(storedLang)) {
    return storedLang;
  }
  
  // Detectar idioma del navegador
  const browserLang = navigator.language.split('-')[0];
  if (SUPPORTED_LANGUAGES.includes(browserLang)) {
    return browserLang;
  }
  
  return DEFAULT_LANGUAGE;
}

/**
 * Guarda el idioma seleccionado en localStorage
 * @param {string} lang Código del idioma
 */
function setLanguage(lang) {
  if (!SUPPORTED_LANGUAGES.includes(lang)) {
    console.warn(`Idioma no soportado: ${lang}`);
    return false;
  }
  
  currentLanguage = lang;
  localStorage.setItem('msl_language', lang);
  
  // Actualizar parámetro URL sin recargar
  const url = new URL(window.location);
  url.searchParams.set('lang', lang);
  window.history.replaceState({}, '', url);
  return true;
}

/**
 * Carga las traducciones del idioma especificado
 * @param {string} lang Código del idioma
 * @returns {Promise<Object>} Promise que resuelve con las traducciones
 */
async function loadTranslations(lang = null) {
  if (!lang) {
    lang = getCurrentLanguage();
  }
  
  const translationsPath = `src/translations/${lang}.json`;
  
  try {
    const response = await fetch(translationsPath);
    if (!response.ok) {
      throw new Error(`Error al cargar traducciones: ${response.status}`);
    }
    const data = await response.json();
    translations = data;
    currentLanguage = lang;
    return data;
  } catch (error) {
    console.error('Error cargando traducciones:', error);
    
    // Intentar cargar idioma por defecto si falla
    if (lang !== DEFAULT_LANGUAGE) {
      return loadTranslations(DEFAULT_LANGUAGE);
    }
    
    return {};
  }
}

/**
 * Obtiene una traducción específica
 * @param {string} key Clave de la traducción (puede ser anidada con puntos, ej: "nav.home")
 * @returns {string} Texto traducido o la clave si no se encuentra
 */
function t(key) {
  if (!translations || Object.keys(translations).length === 0) {
    return key;
  }
  
  const keys = key.split('.');
  let value = translations;
  
  for (const k of keys) {
    if (value && typeof value === 'object' && k in value) {
      value = value[k];
    } else {
      return key;
    }
  }
  
  return typeof value === 'string' ? value : key;
}

/**
 * Aplica traducciones a todos los elementos con atributo data-i18n
 */
function applyTranslations() {
  const elements = document.querySelectorAll('[data-i18n]');
  
  elements.forEach(element => {
    const key = element.getAttribute('data-i18n');
    const translation = t(key);
    
    if (translation) {
      // Si el elemento es un input, textarea o tiene atributo placeholder
      if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
        if (element.hasAttribute('placeholder')) {
          element.setAttribute('placeholder', translation);
        } else {
          element.value = translation;
        }
      } else if (element.hasAttribute('data-i18n-html')) {
        // Permitir HTML en traducciones
        element.innerHTML = translation;
      } else if (element.hasAttribute('data-i18n-attr')) {
        // Aplicar a un atributo específico
        const attrName = element.getAttribute('data-i18n-attr');
        element.setAttribute(attrName, translation);
      } else {
        // Texto normal
        element.textContent = translation;
      }
    }
  });
  
  const seoTitle = t('seo.title');
  const seoDescription = t('seo.description');
  if (seoTitle && seoTitle !== 'seo.title') {
    document.title = seoTitle;
  }
  const metaDesc = document.querySelector('meta[name="description"]');
  if (metaDesc && seoDescription && seoDescription !== 'seo.description') {
    metaDesc.setAttribute('content', seoDescription);
  }

  // Actualizar atributo lang del HTML
  document.documentElement.setAttribute('lang', currentLanguage);
}

/**
 * Inicializa el sistema de traducción
 * @returns {Promise<void>}
 */
async function initI18n() {
  currentLanguage = getCurrentLanguage();
  await loadTranslations(currentLanguage);
  applyTranslations();
}

/**
 * Cambia el idioma y aplica las traducciones
 * @param {string} lang Código del idioma
 */
async function changeLanguage(lang) {
  setLanguage(lang);
  await loadTranslations(lang);
  applyTranslations();
  
  // Disparar evento personalizado para que otros scripts puedan reaccionar
  window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang } }));
}

// Exportar funciones para uso global
window.i18n = {
  getCurrentLanguage,
  setLanguage,
  loadTranslations,
  t,
  applyTranslations,
  initI18n,
  changeLanguage,
  SUPPORTED_LANGUAGES,
  DEFAULT_LANGUAGE,
  get translations() {
    return translations;
  }
};

