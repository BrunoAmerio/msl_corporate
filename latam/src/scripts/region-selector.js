/**
 * Region Selector - Funcionalidad para el selector de región en navbar y footer
 * Maneja los dropdowns con opciones LATAM, USA y Nordic
 */

// Variables para el navbar
let isDropdownOpen = false;

/**
 * Alterna la visibilidad del dropdown del selector de región
 */
function toggleRegionDropdown() {
    const dropdown = document.getElementById('regionDropdown');
    const arrow = document.querySelector('.region_display .arrow');
    
    if (!dropdown || !arrow) return;
    
    isDropdownOpen = !isDropdownOpen;
    
    if (isDropdownOpen) {
        dropdown.classList.add('show');
        arrow.classList.add('open');
    } else {
        dropdown.classList.remove('show');
        arrow.classList.remove('open');
    }
}

/**
 * Selecciona una región específica y actualiza la interfaz
 * @param {string} region - La región seleccionada ('USA', 'Nordic')
 */
function selectRegion(region) {
    const dropdown = document.getElementById('regionDropdown');
    const arrow = document.querySelector('.region_display .arrow');
    
    if (!dropdown || !arrow) return;
    
    // Cerrar el dropdown
    dropdown.classList.remove('show');
    arrow.classList.remove('open');
    isDropdownOpen = false;
    
    // Manejar la redirección según la región seleccionada
    handleRegionChange(region);
}

/**
 * Maneja el cambio de región seleccionada
 * @param {string} region - La región seleccionada
 */
function handleRegionChange(region) {
    console.log(`Región seleccionada: ${region}`);
    
    // Redirigir a la URL correspondiente según la región seleccionada
    switch (region) {
        case 'USA':
            // Abrir MSL LATAM en nueva pestaña
            window.open('https://mslusa.com/', '_blank');
            break;
        case 'Nordic':
            // Abrir MSL Nordic en nueva pestaña
            window.open('https://www.mslnordic.com/', '_blank');
            break;
        default:
            console.warn(`Región no reconocida: ${region}`);
    }
}

/**
 * Cierra el dropdown si se hace clic fuera de él
 * @param {Event} event - El evento de clic
 */
function handleClickOutside(event) {
    const regionSelector = document.getElementById('regionSelector');
    const dropdown = document.getElementById('regionDropdown');
    
    if (!regionSelector || !dropdown) return;
    
    if (!regionSelector.contains(event.target) && isDropdownOpen) {
        dropdown.classList.remove('show');
        document.querySelector('.region_display .arrow').classList.remove('open');
        isDropdownOpen = false;
    }
}

/**
 * Inicializa el selector de región
 */
function initRegionSelector() {
    // Agregar event listener para cerrar el dropdown al hacer clic fuera
    document.addEventListener('click', handleClickOutside);
    
    // Cerrar dropdown con la tecla Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isDropdownOpen) {
            const dropdown = document.getElementById('regionDropdown');
            const arrow = document.querySelector('.region_display .arrow');
            
            if (dropdown && arrow) {
                dropdown.classList.remove('show');
                arrow.classList.remove('open');
                isDropdownOpen = false;
            }
        }
    });
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRegionSelector);
} else {
    initRegionSelector();
}

/**
 * REGION SELECTOR FOOTER - Funcionalidad para el selector de región en el footer
 * Maneja el dropdown con opciones LATAM, USA y Nordic
 */

// Variables para el footer
let isFooterDropdownOpen = false;

/**
 * Alterna la visibilidad del dropdown del selector de región en el footer
 */
function toggleRegionDropdownFooter() {
    const dropdown = document.getElementById('regionDropdownFooter');
    const arrow = document.querySelector('#regionSelectorFooter .region_display .arrow');
    
    if (!dropdown || !arrow) return;
    
    isFooterDropdownOpen = !isFooterDropdownOpen;
    
    if (isFooterDropdownOpen) {
        dropdown.classList.add('show');
        arrow.classList.add('open');
    } else {
        dropdown.classList.remove('show');
        arrow.classList.remove('open');
    }
}

/**
 * Selecciona una región específica en el footer y actualiza la interfaz
 * @param {string} region - La región seleccionada ('USA', 'Nordic')
 */
function selectRegionFooter(region) {
    const dropdown = document.getElementById('regionDropdownFooter');
    const arrow = document.querySelector('#regionSelectorFooter .region_display .arrow');
    const selectedRegion = document.getElementById('selectedRegionFooter');
    
    if (!dropdown || !arrow || !selectedRegion) return;
    
    // Cerrar el dropdown
    dropdown.classList.remove('show');
    arrow.classList.remove('open');
    isFooterDropdownOpen = false;
    
    // Actualizar el texto mostrado
    selectedRegion.textContent = region;
    
    // Manejar la redirección según la región seleccionada
    handleRegionChange(region);
}

/**
 * Cierra el dropdown del footer si se hace clic fuera de él
 * @param {Event} event - El evento de clic
 */
function handleFooterClickOutside(event) {
    const regionSelector = document.getElementById('regionSelectorFooter');
    const dropdown = document.getElementById('regionDropdownFooter');
    
    if (!regionSelector || !dropdown) return;
    
    if (!regionSelector.contains(event.target) && isFooterDropdownOpen) {
        dropdown.classList.remove('show');
        document.querySelector('#regionSelectorFooter .region_display .arrow').classList.remove('open');
        isFooterDropdownOpen = false;
    }
}

/**
 * Inicializa el selector de región del footer
 */
function initFooterRegionSelector() {
    // Agregar event listener para cerrar el dropdown al hacer clic fuera
    document.addEventListener('click', handleFooterClickOutside);
    
    // Cerrar dropdown con la tecla Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isFooterDropdownOpen) {
            const dropdown = document.getElementById('regionDropdownFooter');
            const arrow = document.querySelector('#regionSelectorFooter .region_display .arrow');
            
            if (dropdown && arrow) {
                dropdown.classList.remove('show');
                arrow.classList.remove('open');
                isFooterDropdownOpen = false;
            }
        }
    });
}

/**
 * LOGIN DROPDOWN - Funcionalidad para el dropdown de login
 * Maneja el dropdown con opciones Clientes y Agentes
 */

let isLoginDropdownOpen = false;

/**
 * Alterna la visibilidad del dropdown de login
 * @param {Event} event - El evento de clic
 */
function toggleLoginDropdown(event) {
    event.preventDefault();
    
    const dropdown = document.getElementById('loginDropdownContent');
    const loginDropdown = document.getElementById('loginDropdown');
    
    if (!dropdown || !loginDropdown) return;
    
    isLoginDropdownOpen = !isLoginDropdownOpen;
    
    if (isLoginDropdownOpen) {
        dropdown.classList.add('show');
        loginDropdown.classList.add('active');
    } else {
        dropdown.classList.remove('show');
        loginDropdown.classList.remove('active');
    }
}

/**
 * Cierra el dropdown de login si se hace clic fuera de él
 * @param {Event} event - El evento de clic
 */
function handleLoginClickOutside(event) {
    const loginDropdown = document.getElementById('loginDropdown');
    const dropdown = document.getElementById('loginDropdownContent');
    
    if (!loginDropdown || !dropdown) return;
    
    if (!loginDropdown.contains(event.target) && isLoginDropdownOpen) {
        dropdown.classList.remove('show');
        loginDropdown.classList.remove('active');
        isLoginDropdownOpen = false;
    }
}

/**
 * Inicializa el dropdown de login
 */
function initLoginDropdown() {
    // Agregar event listener para cerrar el dropdown al hacer clic fuera
    document.addEventListener('click', handleLoginClickOutside);
    
    // Cerrar dropdown con la tecla Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isLoginDropdownOpen) {
            const dropdown = document.getElementById('loginDropdownContent');
            const loginDropdown = document.getElementById('loginDropdown');
            
            if (dropdown && loginDropdown) {
                dropdown.classList.remove('show');
                loginDropdown.classList.remove('active');
                isLoginDropdownOpen = false;
            }
        }
    });
}

// Inicializar el dropdown de login cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoginDropdown);
} else {
    initLoginDropdown();
}

// Inicializar el selector de región del footer cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFooterRegionSelector);
} else {
    initFooterRegionSelector();
}

/**
 * REGION SELECTOR MOBILE - Funcionalidad para el selector de región en el menú mobile
 */

// Variable para el dropdown mobile
let isMobileDropdownOpen = false;

/**
 * Alterna la visibilidad del dropdown del selector de región en mobile
 */
function toggleRegionDropdownMobile() {
    const dropdown = document.getElementById('regionDropdownMobile');
    const arrow = document.querySelector('.region_selector_mobile .region_display .arrow');
    
    if (!dropdown || !arrow) return;
    
    isMobileDropdownOpen = !isMobileDropdownOpen;
    
    if (isMobileDropdownOpen) {
        dropdown.classList.add('show');
        arrow.classList.add('open');
    } else {
        dropdown.classList.remove('show');
        arrow.classList.remove('open');
    }
}

/**
 * Selecciona una región específica en el mobile y actualiza la interfaz
 * @param {string} region - La región seleccionada ('USA', 'Nordic')
 */
function selectRegionMobile(region) {
    const dropdown = document.getElementById('regionDropdownMobile');
    const arrow = document.querySelector('.region_selector_mobile .region_display .arrow');
    const selectedRegion = document.getElementById('selectedRegionMobile');
    
    if (!dropdown || !arrow || !selectedRegion) return;
    
    // Cerrar el dropdown
    dropdown.classList.remove('show');
    arrow.classList.remove('open');
    isMobileDropdownOpen = false;
    
    // Actualizar el texto mostrado
    selectedRegion.textContent = region;
    
    // Manejar la redirección según la región seleccionada
    handleRegionChange(region);
}

/**
 * Cierra el dropdown mobile si se hace clic fuera de él
 * @param {Event} event - El evento de clic
 */
function handleMobileClickOutside(event) {
    const regionSelectorMobile = document.querySelector('.region_selector_mobile');
    const dropdown = document.getElementById('regionDropdownMobile');
    
    if (!regionSelectorMobile || !dropdown) return;
    
    if (!regionSelectorMobile.contains(event.target) && isMobileDropdownOpen) {
        dropdown.classList.remove('show');
        document.querySelector('.region_selector_mobile .region_display .arrow').classList.remove('open');
        isMobileDropdownOpen = false;
    }
}

/**
 * Inicializa el selector de región mobile
 */
function initMobileRegionSelector() {
    // Agregar event listener para cerrar el dropdown al hacer clic fuera
    document.addEventListener('click', handleMobileClickOutside);
    
    // Cerrar dropdown con la tecla Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isMobileDropdownOpen) {
            const dropdown = document.getElementById('regionDropdownMobile');
            const arrow = document.querySelector('.region_selector_mobile .region_display .arrow');
            
            if (dropdown && arrow) {
                dropdown.classList.remove('show');
                arrow.classList.remove('open');
                isMobileDropdownOpen = false;
            }
        }
    });
}

// Inicializar el selector de región mobile cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileRegionSelector);
} else {
    initMobileRegionSelector();
}

/**
 * REGION SELECTOR MOBILE FOOTER - Funcionalidad para el selector de región en el footer mobile
 */

// Variable para el dropdown mobile footer
let isMobileFooterDropdownOpen = false;

/**
 * Alterna la visibilidad del dropdown del selector de región en el footer mobile
 */
function toggleRegionDropdownMobileFooter() {
    const dropdown = document.getElementById('regionDropdownMobileFooter');
    const arrow = document.querySelector('.region_selector_mobile .region_display .arrow');
    
    if (!dropdown || !arrow) return;
    
    isMobileFooterDropdownOpen = !isMobileFooterDropdownOpen;
    
    if (isMobileFooterDropdownOpen) {
        dropdown.classList.add('show');
        arrow.classList.add('open');
    } else {
        dropdown.classList.remove('show');
        arrow.classList.remove('open');
    }
}

/**
 * Selecciona una región específica en el footer mobile y actualiza la interfaz
 * @param {string} region - La región seleccionada ('USA', 'Nordic')
 */
function selectRegionMobileFooter(region) {
    const dropdown = document.getElementById('regionDropdownMobileFooter');
    const arrow = document.querySelector('.region_selector_mobile .region_display .arrow');
    const selectedRegion = document.getElementById('selectedRegionMobileFooter');
    
    if (!dropdown || !arrow || !selectedRegion) return;
    
    // Cerrar el dropdown
    dropdown.classList.remove('show');
    arrow.classList.remove('open');
    isMobileFooterDropdownOpen = false;
    
    // Actualizar el texto mostrado
    selectedRegion.textContent = region;
    
    // Manejar la redirección según la región seleccionada
    handleRegionChange(region);
}

/**
 * Cierra el dropdown mobile footer si se hace clic fuera de él
 * @param {Event} event - El evento de clic
 */
function handleMobileFooterClickOutside(event) {
    const regionSelectorMobileFooter = document.querySelector('.mobile .region_selector_mobile');
    const dropdown = document.getElementById('regionDropdownMobileFooter');
    
    if (!regionSelectorMobileFooter || !dropdown) return;
    
    if (!regionSelectorMobileFooter.contains(event.target) && isMobileFooterDropdownOpen) {
        dropdown.classList.remove('show');
        document.querySelector('.mobile .region_selector_mobile .region_display .arrow').classList.remove('open');
        isMobileFooterDropdownOpen = false;
    }
}

/**
 * Inicializa el selector de región del footer mobile
 */
function initMobileFooterRegionSelector() {
    // Agregar event listener para cerrar el dropdown al hacer clic fuera
    document.addEventListener('click', handleMobileFooterClickOutside);
    
    // Cerrar dropdown con la tecla Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isMobileFooterDropdownOpen) {
            const dropdown = document.getElementById('regionDropdownMobileFooter');
            const arrow = document.querySelector('.mobile .region_selector_mobile .region_display .arrow');
            
            if (dropdown && arrow) {
                dropdown.classList.remove('show');
                arrow.classList.remove('open');
                isMobileFooterDropdownOpen = false;
            }
        }
    });
}

// Inicializar el selector de región del footer mobile cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileFooterRegionSelector);
} else {
    initMobileFooterRegionSelector();
}