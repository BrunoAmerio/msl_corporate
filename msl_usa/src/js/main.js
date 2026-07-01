document.addEventListener('DOMContentLoaded', async () => {
  // Inicializar sistema de traducción
  await window.i18n.initI18n();
  
  const langButton = document.getElementById('langButton');
  const langDropdown = document.getElementById('langDropdown');
  const currentLangDisplay = document.getElementById('currentLangDisplay');

  // Función para actualizar el display del idioma actual
  const updateLangDisplay = () => {
    const currentLang = window.i18n.getCurrentLanguage();
    const langMap = { es: 'ES', en: 'EN', pt: 'PT', sv: 'SV', no: 'NO' };
    if (currentLangDisplay) {
      currentLangDisplay.textContent = langMap[currentLang] || 'EN';
    }
  };

  // Actualizar display inicial
  updateLangDisplay();

  langButton.addEventListener('click', (e) => {
    e.stopPropagation();
    langDropdown.classList.toggle('show');
  });

  document.addEventListener('click', () => {
    langDropdown.classList.remove('show');
  });

  langDropdown.addEventListener('click', (e) => {
    e.stopPropagation();
  });

  const langOptions = document.querySelectorAll('.lang-option');
  langOptions.forEach(option => {
    option.addEventListener('click', async (e) => {
      e.preventDefault();
      e.stopPropagation();
      const selectedLang = option.getAttribute('data-lang');
      
      if (!selectedLang) {
        console.warn('No se encontró el atributo data-lang en la opción seleccionada');
        langDropdown.classList.remove('show');
        return;
      }
      
      if (!window.i18n.SUPPORTED_LANGUAGES.includes(selectedLang)) {
        console.warn(`Idioma no soportado: ${selectedLang}. Idiomas disponibles:`, window.i18n.SUPPORTED_LANGUAGES);
        langDropdown.classList.remove('show');
        return;
      }
      
      try {
        await window.i18n.changeLanguage(selectedLang);
        updateLangDisplay();
        updateNetworkCardData();
        updateJourneyDetails();
        
        // Si hay un marker activo, actualizar su card
        if (currentNetworkMarkerKey) {
          showNetworkCard(currentNetworkMarkerKey);
        }
        
        // Si hay un journey item activo, actualizar sus detalles
        if (activeJourneyItem) {
          activateJourneyItem(activeJourneyItem);
        }
      } catch (error) {
        console.error('Error al cambiar el idioma:', error);
      }
      
      langDropdown.classList.remove('show');
    });
  });

  const networkMarkers = document.querySelectorAll('.network-marker');
  const networkCard = document.querySelector('.network-card');
  const cardFields = networkCard
    ? {
        eyebrow: networkCard.querySelector('[data-card-field="eyebrow"]'),
        title: networkCard.querySelector('[data-card-field="title"]'),
        label: networkCard.querySelector('[data-card-field="label"]'),
        countries: networkCard.querySelector('[data-card-field="countries"]'),
        cta: networkCard.querySelector('[data-card-field="cta"]'),
        link: networkCard.querySelector('[data-card-field="link"]'),
      }
    : null;

  // Función para obtener datos de network card desde traducciones
  const getNetworkCardData = () => {
    const translations = window.i18n.translations || {};
    
    // Obtener countries desde las traducciones directamente
    const getCountries = (key) => {
      const keys = key.split('.');
      let value = translations;
      for (const k of keys) {
        if (value && typeof value === 'object' && k in value) {
          value = value[k];
        } else {
          return [];
        }
      }
      return Array.isArray(value) ? value : [];
    };

    return {
      latam: {
        eyebrow: window.i18n.t('network.latam.eyebrow'),
        title: window.i18n.t('network.latam.title'),
        countries: getCountries('network.latam.countries'),
        isUSA: false,
        cta: window.i18n.t('network.latam.cta'),
        url: 'https://www.msllatam.com',
      },
      usa: {
        eyebrow: window.i18n.t('network.usa.eyebrow'),
        title: window.i18n.t('network.usa.title'),
        countries: getCountries('network.usa.countries'),
        isUSA: true,
        cta: window.i18n.t('network.usa.cta'),
        url: 'https://mslusa.com/',
      },
      europa: {
        eyebrow: window.i18n.t('network.europa.eyebrow'),
        title: window.i18n.t('network.europa.title'),
        countries: getCountries('network.europa.countries'),
        isUSA: false,
        cta: window.i18n.t('network.europa.cta'),
        url: 'https://www.mslnordic.com/',
      },
    };
  };

  let networkCardData = getNetworkCardData();

  // Función para actualizar networkCardData cuando cambia el idioma
  const updateNetworkCardData = () => {
    networkCardData = getNetworkCardData();
  };

  let activeMarker = null;
  let currentNetworkMarkerKey = null;
  let isNetworkCardAnimating = false;
  let pendingNetworkMarkerKey = null;

  const fillNetworkCard = (cardData) => {
    if (!cardFields) {
      return;
    }

    // Asegurar que tenemos los datos actualizados
    if (!cardData || !cardData.eyebrow) {
      return;
    }

    cardFields.eyebrow.textContent = cardData.eyebrow;
    cardFields.title.textContent = cardData.title;

    // Determinar el label según la región (USA usa "Ciudades", las demás "Países")
    const labelKey = cardData.isUSA ? 'network.cities_label' : 'network.countries_label';
    cardFields.label.textContent = window.i18n.t(labelKey);

    cardFields.countries.innerHTML = '';
    
    // Manejar countries como array o string
    const countriesArray = Array.isArray(cardData.countries) 
      ? cardData.countries 
      : (typeof cardData.countries === 'string' ? [cardData.countries] : []);
    
    countriesArray.forEach((country) => {
      const tag = document.createElement('span');
      tag.className = 'network-card__tag';
      tag.textContent = country;
      cardFields.countries.append(tag);
    });

    cardFields.cta.textContent = cardData.cta;
    cardFields.link.href = cardData.url;
  };

  const runNetworkCardEnter = (markerKey) => {
    if (!networkCard) {
      return;
    }

    const cardData = networkCardData[markerKey];

    if (!cardData) {
      hideNetworkCard();
      return;
    }

    fillNetworkCard(cardData);
    currentNetworkMarkerKey = markerKey;
    networkCard.setAttribute('aria-hidden', 'false');

    const handleEnterEnd = (event) => {
      if (event.target !== networkCard || event.propertyName !== 'transform') {
        return;
      }

      networkCard.removeEventListener('transitionend', handleEnterEnd);
      isNetworkCardAnimating = false;

      if (pendingNetworkMarkerKey && pendingNetworkMarkerKey !== currentNetworkMarkerKey) {
        const nextMarker = pendingNetworkMarkerKey;
        pendingNetworkMarkerKey = null;
        transitionNetworkCard(nextMarker);
      }
    };

    networkCard.addEventListener('transitionend', handleEnterEnd);
    networkCard.classList.add('is-visible');
  };

  const hideNetworkCard = () => {
    if (!networkCard) {
      return;
    }

    networkCard.classList.remove('is-transitioning-out');
    networkCard.classList.remove('is-visible');
    networkCard.setAttribute('aria-hidden', 'true');
    currentNetworkMarkerKey = null;
    pendingNetworkMarkerKey = null;
    isNetworkCardAnimating = false;
  };

  const transitionNetworkCard = (markerKey) => {
    if (!networkCard) {
      return;
    }

    if (!networkCard.classList.contains('is-visible')) {
      networkCard.classList.remove('is-transitioning-out');
      networkCard.classList.remove('is-visible');
      isNetworkCardAnimating = true;
      runNetworkCardEnter(markerKey);
      return;
    }

    if (isNetworkCardAnimating) {
      pendingNetworkMarkerKey = markerKey;
      return;
    }

    if (currentNetworkMarkerKey === markerKey) {
      return;
    }

    isNetworkCardAnimating = true;

    const cardData = networkCardData[markerKey];

    if (!cardData) {
      hideNetworkCard();
      return;
    }

    const handleExitEnd = (event) => {
      if (event.target !== networkCard || event.propertyName !== 'transform') {
        return;
      }

      networkCard.removeEventListener('transitionend', handleExitEnd);
      networkCard.classList.remove('is-visible');
      networkCard.classList.remove('is-transitioning-out');
      networkCard.setAttribute('aria-hidden', 'true');
      runNetworkCardEnter(markerKey);
    };

    networkCard.addEventListener('transitionend', handleExitEnd);
    networkCard.classList.add('is-transitioning-out');
  };

  const showNetworkCard = (markerKey) => {
    transitionNetworkCard(markerKey);
  };

  const deactivateActiveMarker = ({ hideCard = true } = {}) => {
    if (activeMarker) {
      activeMarker.classList.remove('is-active');
      activeMarker = null;
    }

    if (hideCard) {
      hideNetworkCard();
    }
  };

  networkMarkers.forEach(marker => {
    marker.addEventListener('click', (event) => {
      event.stopPropagation();

      if (activeMarker === marker) {
        return;
      }

      deactivateActiveMarker({ hideCard: false });
      marker.classList.add('is-active');
      activeMarker = marker;
      showNetworkCard(marker.dataset.marker);
    });
  });

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.network-marker')) {
      deactivateActiveMarker();
    }
  });

  const journeyItems = document.querySelectorAll('.journey-section__item');
  const journeyProgressIndicator = document.querySelector('.journey-section__progress-indicator');
  const journeyItemsArray = Array.from(journeyItems);
  const journeyItemsCount = journeyItemsArray.length;

  if (journeyProgressIndicator && journeyItemsCount > 0) {
    journeyProgressIndicator.style.width = `${100 / journeyItemsCount}%`;
  }

  // Función para obtener journey details desde traducciones
  const getJourneyDetails = () => {
    return {
      latam: window.i18n.t('journey.latam.details'),
      usa: window.i18n.t('journey.usa.details'),
      nordic: window.i18n.t('journey.nordic.details'),
    };
  };

  // Función para obtener journey summaries desde traducciones
  const getJourneySummaries = () => {
    return {
      latam: { 
        title: window.i18n.t('journey.latam.label'), 
        copy: window.i18n.t('journey.latam.meta_text') 
      },
      usa: { 
        title: window.i18n.t('journey.usa.label'), 
        copy: window.i18n.t('journey.usa.meta_text') 
      },
      nordic: { 
        title: window.i18n.t('journey.nordic.label'), 
        copy: window.i18n.t('journey.nordic.meta_text') 
      },
    };
  };

  let journeyDetails = getJourneyDetails();
  let journeySummaries = getJourneySummaries();

  // Función para actualizar journey data cuando cambia el idioma
  const updateJourneyDetails = () => {
    journeyDetails = getJourneyDetails();
    journeySummaries = getJourneySummaries();
  };

  const mainCardTitle = document.querySelector('.history-summary__title');
  const mainCardDescription = document.querySelector('.history-summary__description');

  const updateMainSummary = (summaryKey) => {
    if (!mainCardTitle || !mainCardDescription) {
      return;
    }

    const summary = journeySummaries[summaryKey];

    if (!summary) {
      return;
    }

    mainCardTitle.textContent = summary.title;
    mainCardDescription.textContent = summary.copy;
  };

  let activeJourneyItem = null;

  const moveJourneyProgress = (targetIndex) => {
    if (!journeyProgressIndicator || journeyItemsCount === 0) {
      return;
    }

    const clampedIndex = Math.max(0, Math.min(targetIndex, journeyItemsCount - 1));
    journeyProgressIndicator.style.transform = `translateX(${clampedIndex * 100}%)`;
    journeyProgressIndicator.style.opacity = '1';
  };

  const deactivateJourneyItem = () => {
    if (!activeJourneyItem) {
      return;
    }

    activeJourneyItem.classList.remove('is-active');
    activeJourneyItem = null;

    if (journeyProgressIndicator) {
      journeyProgressIndicator.style.opacity = journeyItemsCount > 0 ? '0' : '0';
    }
  };

  const activateJourneyItem = (item) => {
    const journeyKey = item.dataset.journey;
    const detailNode = item.querySelector('.journey-card__meta-details');

    if (!journeyKey || !detailNode) {
      return;
    }

    // Actualizar journeyDetails si es necesario
    if (!journeyDetails[journeyKey]) {
      journeyDetails = getJourneyDetails();
    }
    
    const detailCopy = journeyDetails[journeyKey];

    detailNode.textContent = detailCopy || '';
    item.classList.add('is-active');
    activeJourneyItem = item;

    // Actualizar summaries si es necesario
    if (!journeySummaries[journeyKey]) {
      journeySummaries = getJourneySummaries();
    }
    
    updateMainSummary(journeyKey);

    if (journeyProgressIndicator) {
      const itemIndex = journeyItemsArray.indexOf(item);
      if (itemIndex >= 0) {
        moveJourneyProgress(itemIndex);
      }
    }
  };

  const defaultJourneyKey = 'latam';
  const defaultJourneyItem = journeyItemsArray.find(
    (item) => item.dataset.journey === defaultJourneyKey,
  );

  if (defaultJourneyItem) {
    activateJourneyItem(defaultJourneyItem);
  }

  journeyItemsArray.forEach((item) => {
    item.addEventListener('click', (event) => {
      event.stopPropagation();

      if (activeJourneyItem === item) {
        activateJourneyItem(item);
        return;
      }

      deactivateJourneyItem();
      activateJourneyItem(item);
    });
  });

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.journey-section__item')) {
      deactivateJourneyItem();
    }
  });

  const SCROLL_OFFSET = 0;
  const smoothScrollLinks = document.querySelectorAll('[data-scroll-target]');

  const scrollWithOffset = (targetSelector) => {
    if (!targetSelector) {
      return;
    }

    const targetElement = document.querySelector(targetSelector);

    if (!targetElement) {
      return;
    }

    const elementTop = targetElement.getBoundingClientRect().top + window.pageYOffset;
    const targetPosition = Math.max(elementTop - SCROLL_OFFSET, 0);

    window.scrollTo({
      top: targetPosition,
      behavior: 'smooth',
    });
  };

  smoothScrollLinks.forEach((link) => {
    link.addEventListener('click', (event) => {
      const targetSelector = link.getAttribute('data-scroll-target');

      if (!targetSelector) {
        return;
      }

      event.preventDefault();
      scrollWithOffset(targetSelector);
    });
  });

  // Hacer las region cards clickeables en dispositivos móviles/tablets
  const regionCards = document.querySelectorAll('.region-card');
  const isMobileOrTablet = () => window.matchMedia('(max-width: 768px)').matches;

  regionCards.forEach((card) => {
    const websiteButton = card.querySelector('.website-button');
    
    if (!websiteButton) {
      return;
    }

    const cardUrl = websiteButton.getAttribute('href');

    card.addEventListener('click', (event) => {
      // Solo aplicar en móviles y tablets (max-width: 768px)
      if (!isMobileOrTablet()) {
        return;
      }

      // Evitar que el click se propague si se hace click directamente en el botón
      if (event.target.closest('.website-button')) {
        return;
      }

      // Abrir la URL en una nueva ventana
      if (cardUrl) {
        window.open(cardUrl, '_blank', 'noopener,noreferrer');
      }
    });
  });
});

