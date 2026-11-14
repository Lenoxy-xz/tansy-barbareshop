// assets/js/main.js
// Tansy Barbershop - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {

    // Sidebar pelanggan di layar kecil: tombol buka/tutup + overlay
    const pSidebar = document.getElementById('pelangganSidebar');
    const pToggle  = document.getElementById('pelangganSidebarToggle');
    const pOverlay = document.getElementById('pelangganOverlay');
    if (pSidebar && pToggle) {
        const tutupSidebar = function() {
            pSidebar.classList.remove('open');
            if (pOverlay) pOverlay.classList.remove('show');
            pToggle.setAttribute('aria-expanded', 'false');
        };
        pToggle.addEventListener('click', function() {
            const terbuka = pSidebar.classList.toggle('open');
            if (pOverlay) pOverlay.classList.toggle('show', terbuka);
            pToggle.setAttribute('aria-expanded', String(terbuka));
        });
        if (pOverlay) pOverlay.addEventListener('click', tutupSidebar);
        pSidebar.querySelectorAll('a').forEach(function(a) { a.addEventListener('click', tutupSidebar); });
        window.addEventListener('resize', function() { if (window.innerWidth > 768) tutupSidebar(); });
    }

    // Navbar scroll effect
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.style.padding = '8px 0';
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.5)';
            } else {
                navbar.style.padding = '15px 0';
                navbar.style.boxShadow = 'none';
            }
        });
    }

    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const offset = 70;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });

    // Animasi fade-in saat scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });

});
