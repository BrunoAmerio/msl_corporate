/**
 * Efecto parallax para imagen NLO
 * Versión simplificada y más visible
 */
document.addEventListener('DOMContentLoaded', function() {
  const parallaxImage = document.querySelector('.parallax-image');
  
  if (!parallaxImage) {
    console.log('No se encontró la imagen con clase parallax-image');
    return;
  }
  
  console.log('Imagen parallax encontrada:', parallaxImage);
  
  // Configuración del efecto parallax
  const parallaxSpeed = 0.2; // Velocidad del efecto (más lento para ser más visible)
  
  function updateParallax() {
    const scrolled = window.pageYOffset;
    
    // Calcular el offset basado en el scroll
    const offset = scrolled * parallaxSpeed;
    
    // Aplicar el efecto parallax
    parallaxImage.style.transform = `translateY(${offset - 90}px)`;
    
  }
  
  // Event listener directo para mejor compatibilidad
  window.addEventListener('scroll', function() {
    updateParallax();
  });
  
  // Ejecutar una vez al cargar
  updateParallax();
  
  console.log('Efecto parallax inicializado - versión simplificada');
});
