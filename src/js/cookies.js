/**
 * Consentimiento de cookies.
 * Google Tag Manager (analítica) solo se carga si la persona acepta el banner.
 * La decisión se guarda en localStorage (clave msl_cookie_consent).
 */
(function () {
  var GTM_ID = 'GTM-NPRFRX97';
  var STORAGE_KEY = 'msl_cookie_consent';

  function readConsent() {
    try { return localStorage.getItem(STORAGE_KEY); } catch (e) { return null; }
  }

  function saveConsent(value) {
    try { localStorage.setItem(STORAGE_KEY, value); } catch (e) { /* sin almacenamiento: se vuelve a preguntar */ }
  }

  function loadGTM() {
    if (window.__gtmLoaded) return;
    window.__gtmLoaded = true;
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
    var s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtm.js?id=' + GTM_ID;
    document.head.appendChild(s);
  }

  document.addEventListener('DOMContentLoaded', function () {
    var consent = readConsent();
    if (consent === 'accepted') {
      loadGTM();
      return;
    }
    if (consent === 'declined') return;

    var banner = document.getElementById('cookieBanner');
    if (!banner) return;
    banner.hidden = false;

    var close = function (value) {
      saveConsent(value);
      banner.hidden = true;
      if (value === 'accepted') loadGTM();
    };

    var accept = document.getElementById('cookieAccept');
    var decline = document.getElementById('cookieDecline');
    if (accept) accept.addEventListener('click', function () { close('accepted'); });
    if (decline) decline.addEventListener('click', function () { close('declined'); });
  });
})();
