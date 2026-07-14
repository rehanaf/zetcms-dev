<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // ==========================================
        // 1. BULLETPROOF CAROUSEL (Custom Vanilla Fade Engine)
        // ==========================================
        const slides = document.querySelectorAll('.carousel-item-custom');
        const indicators = document.querySelectorAll('.carousel-indicators-custom button');
        const prevBtn = document.querySelector('.carousel-control-prev-custom');
        const nextBtn = document.querySelector('.carousel-control-next-custom');
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        let slideInterval;

        function showSlide(index) {
            if (slides.length === 0) return;
            
            // Wrap index
            if (index >= totalSlides) index = 0;
            if (index < 0) index = totalSlides - 1;

            // Toggle Active Classes
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });

            // Toggle Indicators
            indicators.forEach((indicator, i) => {
                if (i === index) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });

            currentSlide = index;
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        // Start Auto-Play Timer
        function startAutoplay() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Bind Event Listeners to Controls
        if (prevBtn) {
            prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                prevSlide();
                startAutoplay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
                nextSlide();
                startAutoplay();
            });
        }

        // Bind Event Listeners to Indicators
        indicators.forEach((btn, i) => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                showSlide(i);
                startAutoplay();
            });
        });

        if(slides.length > 0) {
            showSlide(currentSlide);
            startAutoplay();
        }

        // ==========================================
        // 2. ACTIVE NAV HIGHLIGHT ON SCROLL (ScrollSpy)
        // ==========================================
        const sections = document.querySelectorAll('section');
        const menuLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= (sectionTop - 150)) {
                    current = section.getAttribute('id');
                }
            });

            // Highlight desktop links
            menuLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (current && href && href.includes(current)) {
                    link.classList.add('active');
                }
            });

            // Scroll Top Button visibility logic
            const scrollTopBtn = document.getElementById('scrollTopBtn');
            if (scrollTopBtn) {
                if (window.scrollY > 400) {
                    scrollTopBtn.classList.add('show');
                } else {
                    scrollTopBtn.classList.remove('show');
                }
            }
        });

        // ==========================================
        // 3. OFFCANVAS DRAWER (Mobile Navigation Engine)
        // ==========================================
        const drawerTrigger = document.getElementById('drawerTrigger');
        const drawerClose = document.getElementById('drawerClose');
        const mobileDrawer = document.getElementById('mobileDrawer');
        const drawerOverlay = document.getElementById('drawerOverlay');
        const drawerLinks = document.querySelectorAll('.drawer-link:not([id^="drawerDropdownToggle"]), .drawer-sublink');

        function openDrawer() {
            if (mobileDrawer && drawerOverlay) {
                mobileDrawer.classList.add('show');
                drawerOverlay.classList.add('show');
                document.body.style.overflow = 'hidden'; 
            }
        }

        function closeDrawer() {
            if (mobileDrawer && drawerOverlay) {
                mobileDrawer.classList.remove('show');
                drawerOverlay.classList.remove('show');
                document.body.style.overflow = ''; 
            }
        }

        if (drawerTrigger) {
            drawerTrigger.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openDrawer();
            });
        }

        if (drawerClose) {
            drawerClose.addEventListener('click', closeDrawer);
        }

        if (drawerOverlay) {
            drawerOverlay.addEventListener('click', closeDrawer);
        }

        drawerLinks.forEach(link => {
            link.addEventListener('click', closeDrawer);
        });

        const drawerDropdownToggles = document.querySelectorAll('[id^="drawerDropdownToggle"]');
        drawerDropdownToggles.forEach((toggle, index) => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const drawerSubmenu = document.getElementById('drawerSubmenu' + index);
                const drawerChevron = document.getElementById('drawerChevron' + index);
                if(drawerSubmenu && drawerChevron) {
                    drawerSubmenu.classList.toggle('show');
                    if (drawerSubmenu.classList.contains('show')) {
                        drawerChevron.style.transform = 'rotate(180deg)';
                    } else {
                        drawerChevron.style.transform = 'rotate(0deg)';
                    }
                }
            });
        });

        // ==========================================
        // 4. AOS ANIMATION INIT
        // ==========================================
        if (typeof AOS !== 'undefined') {
            AOS.init({
                once: true,
                duration: 800,
                offset: 50
            });
        }

        // ==========================================
        // 5. AJAX FORM SUBMISSION WITH SWEETALERT
        // ==========================================
        const ajaxForms = document.querySelectorAll('.ajax-form');
        ajaxForms.forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Mengirim...';
                submitBtn.disabled = true;

                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            html: result.message || 'Pesan Anda berhasil dikirim.',
                            icon: 'success',
                            confirmButtonColor: '#D4AF37',
                            confirmButtonText: 'Tutup'
                        });
                        form.reset();
                    } else {
                        if (result.errors) {
                            Object.keys(result.errors).forEach(key => {
                                const input = form.querySelector(`[name="${key}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'invalid-feedback font-sans small mt-1';
                                    errorDiv.innerText = result.errors[key][0];
                                    input.parentNode.appendChild(errorDiv);
                                }
                            });
                        }
                        
                        Swal.fire({
                            title: 'Oops!',
                            text: result.message || 'Terjadi kesalahan, silakan periksa isian Anda.',
                            icon: 'error',
                            confirmButtonColor: '#3E2723',
                            confirmButtonText: 'Tutup'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'Error Server',
                        text: 'Terjadi masalah pada server. Silakan coba lagi nanti.',
                        icon: 'error',
                        confirmButtonColor: '#3E2723',
                        confirmButtonText: 'Tutup'
                    });
                } finally {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            });
        });

    });

    // Helper Actions
    function scrollToElement(id) {
        const element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    }
</script>
