/**
 * main.js
 * Gère le comportement de la navigation (mobile/dropdowns)
 * et le décalage du scroll pour les ancres sur un site multi-pages (MPA).
 */

document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.navbar');
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const dropdowns = document.querySelectorAll('.dropdown');

    // --- 1. Gestion du Menu Mobile (Toggle) ---
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
            
            // Toggle de l'état du menu principal
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            navMenu.classList.toggle('open');
            
            // Fermer tous les sous-menus ouverts lors de l'ouverture/fermeture du menu principal
            dropdowns.forEach(d => d.classList.remove('open'));
        });
    }

    // --- 2. Gestion du Dropdown en mode Mobile (Accordion) ---
    dropdowns.forEach(dropdown => {
        const link = dropdown.querySelector(':scope > a'); // Sélectionne le lien direct enfant

        link.addEventListener('click', (e) => {
            // Seul le mode mobile (< 992px, en se basant sur la Media Query CSS) nécessite le comportement accordéon
            if (window.innerWidth <= 992) {
                // Si le dropdown est déjà ouvert, le fermer. Sinon, le fermer tous les autres et ouvrir celui-ci.
                const wasOpen = dropdown.classList.contains('open');
                
                // Si c'est un lien vers une page réelle (pas seulement un toggle), laisser le défaut
                if (!link.getAttribute('href') || link.getAttribute('href') === '#') {
                    e.preventDefault();
                }

                // Fermer tous les autres dropdowns
                dropdowns.forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('open');
                    }
                });

                // Si le lien n'était pas un lien vers une page réelle ou était déjà ouvert, gérer l'accordéon
                if (link.getAttribute('href') === '#' || link.getAttribute('href').endsWith('.html') || !wasOpen) {
                     dropdown.classList.toggle('open');
                }
            }
        });
    });


    // --- 3. Fermeture lors du Clic à l'Extérieur et de la touche Escape (Accessibilité) ---
    
    // Fermeture lors du Clic à l'Extérieur de la navbar
    document.addEventListener('click', (e) => {
        if (navMenu.classList.contains('open') && !navbar.contains(e.target)) {
            navMenu.classList.remove('open');
            if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
        }
        
        // Fermer les dropdowns ouverts si le clic n'était pas dans un dropdown
        if (!e.target.closest('.dropdown')) {
            dropdowns.forEach(d => d.classList.remove('open'));
        }
    });

    // Fermeture avec la touche Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (navMenu.classList.contains('open')) {
                navMenu.classList.remove('open');
                if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
            }
            dropdowns.forEach(d => d.classList.remove('open'));
        }
    });


    // --- 4. Gestion du Scroll pour les Ancres (Fixer la position sous la barre de navigation) ---
    
    // Si la page a été chargée avec une ancre (ex: page.html#section)
    if (window.location.hash) {
        handleAnchorScroll(window.location.hash);
    }

    // Écouteur de clic pour tous les liens d'ancre (ex: <a href="#target">)
    document.querySelectorAll('a[href^="#"]').forEach(anchorLink => {
        anchorLink.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                e.preventDefault();
                // Utiliser l'API History pour mettre à jour l'URL sans recharger
                window.history.pushState(null, '', targetId);
                handleAnchorScroll(targetId);
            }
        });
    });
    
    // Fonction principale de décalage du scroll
    function handleAnchorScroll(hash) {
        const anchorElement = document.querySelector(hash);
        const HEADER_HEIGHT = 70; // Hauteur de la navbar (doit correspondre à votre CSS)
        const OFFSET_BUFFER = 15; // Marge supplémentaire

        if (anchorElement) {
            // Délai nécessaire pour que le navigateur repositionne les éléments avant le calcul du scroll
            // Ceci est une amélioration de votre logique précédente.
            setTimeout(() => {
                const offsetTop = anchorElement.getBoundingClientRect().top + window.scrollY;
                const targetY = offsetTop - HEADER_HEIGHT - OFFSET_BUFFER;

                window.scrollTo({
                    top: targetY,
                    behavior: 'smooth'
                });
            }, 100); 
        }
    }
});