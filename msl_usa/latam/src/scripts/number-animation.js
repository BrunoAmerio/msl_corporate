// Función para animar los números
function animateValue(obj, start, end, duration, prefix = '', suffix = '') {
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    obj.textContent = prefix + Math.floor(progress * (end - start) + start) + suffix;
    if (progress < 1) {
      window.requestAnimationFrame(step);
    }
  };
  window.requestAnimationFrame(step);
}

// Función para manejar valores con K
function handleNumberAnimation(number) {
  const text = number.textContent;
  if (text.includes('K')) {
    const endValue = parseFloat(text);
    animateValue(number, 0, endValue, 2000, '', 'K');
  } else if (text.includes('+')) {
    const endValue = parseFloat(text);
    animateValue(number, 0, endValue, 2000, '+', '');
  } else {
    const endValue = parseFloat(text);
    animateValue(number, 0, endValue, 2000);
  }
}

// Configurar el IntersectionObserver
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // Animar solo los números dentro del article
      const numbers = entry.target.querySelector('article').querySelectorAll('h3');
      numbers.forEach(handleNumberAnimation);
      
      // Dejar de observar después de la animación
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

// Observar la sección msl-group
const mslGroupSection = document.getElementById('msl-group');
observer.observe(mslGroupSection);