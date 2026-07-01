function initTypewriter() {
  const h1Element = document.querySelector('.reval');
  if (!h1Element) return;

  // Inicializar los spans inmediatamente al cargar la página
  if (!h1Element.querySelector('span')) {
    const letters = h1Element.textContent.split('');
    h1Element.innerHTML = letters.map(letter => `<span>${letter}</span>`).join('');
    
    // Inicializar todos los spans con opacidad 0.5
    const spans = h1Element.querySelectorAll('span');
    spans.forEach(span => {
      span.style.opacity = '0.5';
    });
  }
}

function updateTypewriter() {
  const h1Element = document.querySelector('.reval');
  if (!h1Element) return;

  const elementPosition = h1Element.getBoundingClientRect().top;
  const screenPosition = window.innerHeight / 1.3;

  if (elementPosition < screenPosition) {
    const spans = h1Element.querySelectorAll('span');
    const progress = Math.min(1, (screenPosition - elementPosition) / (screenPosition / 2));
    const visibleLetters = Math.floor(spans.length * progress);

    spans.forEach((span, index) => {
      span.style.opacity = index <= visibleLetters ? '1' : '0.5';
    });
  }
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', initTypewriter);

// Actualizar al hacer scroll
document.addEventListener('scroll', updateTypewriter);