document.addEventListener('DOMContentLoaded', function() {
    const hoverVideos = document.querySelectorAll('.hover-video');
    
    hoverVideos.forEach(video => {
        const card = video.closest('.card');
        let pauseTimeout = null;
        
        if (card) {
            // Evento cuando el mouse entra en la card
            card.addEventListener('mouseenter', function() {
                // Cancelar cualquier timeout pendiente
                if (pauseTimeout) {
                    clearTimeout(pauseTimeout);
                    pauseTimeout = null;
                }
                
                video.play().catch(error => {
                    console.log('Error al reproducir video:', error);
                });
            });
            
            // Evento cuando el mouse sale de la card
            card.addEventListener('mouseleave', function() {
                // Programar pausa después de 1 segundo
                pauseTimeout = setTimeout(() => {
                    video.pause();
                    video.currentTime = 0; // Reinicia el video al inicio
                    pauseTimeout = null;
                }, 1000);
            });
            
            // Pausar video si pierde el foco (para accesibilidad)
            card.addEventListener('blur', function() {
                if (pauseTimeout) {
                    clearTimeout(pauseTimeout);
                    pauseTimeout = null;
                }
                video.pause();
                video.currentTime = 0;
            });
        }
    });
});
