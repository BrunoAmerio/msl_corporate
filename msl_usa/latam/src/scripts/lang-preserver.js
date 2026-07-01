/**
 * Script para preservar el parámetro lang en la URL al navegar entre páginas
 * Solo actúa como respaldo para enlaces que no fueron generados por PHP
 */
(function() {
    'use strict';

    // Obtener el parámetro lang de la URL actual
    function getLangFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('lang');
    }

    // Preservar lang en enlaces onclick que usan window.location.href
    function interceptLocationHref() {
        const currentLang = getLangFromURL();
        
        if (!currentLang) {
            return;
        }

        // Interceptar onclick handlers que usan window.location.href
        document.addEventListener('click', function(e) {
            const target = e.target;
            const onclick = target.getAttribute('onclick');
            
            if (onclick && onclick.includes('window.location.href')) {
                // Extraer la URL del onclick
                const match = onclick.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
                if (match && match[1]) {
                    let url = match[1];
                    
                    // Solo procesar URLs internas (.php)
                    if (url.includes('.php') && !url.includes('lang=')) {
                        // Preservar hash si existe
                        const hash = url.includes('#') ? '#' + url.split('#')[1] : '';
                        url = url.split('#')[0];
                        
                        // Añadir parámetro lang
                        const separator = url.includes('?') ? '&' : '?';
                        const newUrl = url + separator + 'lang=' + currentLang + hash;
                        
                        // Actualizar onclick
                        const newOnclick = onclick.replace(match[0], 'window.location.href=\'' + newUrl + '\'');
                        target.setAttribute('onclick', newOnclick);
                    }
                }
            }
        }, true);
    }

    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', interceptLocationHref);
    } else {
        interceptLocationHref();
    }
})();

