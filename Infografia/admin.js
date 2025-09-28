
document.addEventListener('DOMContentLoaded', function() {

    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');


    function showTab(tabId) {
        // Ocultar todos los contenidos de las pestañas
        tabContents.forEach(content => {
            content.style.display = 'none';
        });


        tabLinks.forEach(link => {
            link.classList.remove('active');
        });

        // Mostrar el contenido de la pestaña seleccionada
        const activeContent = document.getElementById(tabId);
        if (activeContent) {
            activeContent.style.display = 'block'; // O 'flex' o 'grid', dependiendo de tu CSS
        }
    }

    tabLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Previene la acción por defecto del enlace (#)

            // Obtiene el ID del contenido a mostrar desde el atributo data-tab
            const tabId = this.getAttribute('data-tab');

       
            showTab(tabId);
            this.classList.add('active');
        });
    });

    const initialActiveLink = document.querySelector('.tab-link.active');
    if (initialActiveLink) {
        const initialTabId = initialActiveLink.getAttribute('data-tab');
        showTab(initialTabId);
    } else if (tabLinks.length > 0) {

        tabLinks[0].click(); 
    }
});