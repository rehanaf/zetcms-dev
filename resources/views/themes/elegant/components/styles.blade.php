<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    :root {
        --color-primary: #3E2723;       /* Deep Rich Chocolate */
        --color-secondary: #5D4037;     /* Warm Medium Brown */
        --color-accent: #D4AF37;        /* Elegant Gold */
        --color-accent-hover: #C5A880;  /* Champagne Gold */
        --color-light: #FDFBF7;         /* Luxury Soft Ivory Cream */
        --color-dark: #1E120F;          /* Dark Chocolate / Near Black */
        --font-serif: 'Cormorant Garamond', serif;
        --font-sans: 'Albert Sans', sans-serif;
        --transition-smooth: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
    }

    html {
        scroll-behavior: smooth;
        scroll-padding-top: 85px;       /* Offset scroll target agar tidak tertutup fixed navbar */
    }

    body {
        font-family: var(--font-sans);
        background-color: var(--color-light);
        color: var(--color-dark);
        padding-top: 75px;              /* Cadangan ruang agar konten tidak tertutup fixed navbar */
    }

    html, body {
        overflow-x: hidden !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    @media (max-width: 991.98px) {
        body {
            padding-top: 70px;          /* Jarak kompensasi mobile yang lebih rapat */
        }
    }

    /* Typography Styling without default CapLock */
    h1, h2, h3, h4, h5, h6, .font-serif {
        font-family: var(--font-serif);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .text-accent {
        color: var(--color-accent);
    }

    .bg-chocolate-dark {
        background-color: var(--color-dark);
    }

    .bg-chocolate {
        background-color: var(--color-primary);
    }

    .bg-cream {
        background-color: var(--color-light);
    }

    /* Customized Buttons - Underlines Removed */
    .btn-elegant {
        background-color: var(--color-accent);
        color: var(--color-dark) !important;
        font-family: var(--font-sans);
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
        padding: 12px 30px;
        border-radius: 4px;
        border: 1px solid var(--color-accent);
        transition: var(--transition-smooth);
        text-decoration: none !important;
    }

    .btn-elegant:hover {
        background-color: transparent;
        color: var(--color-accent) !important;
        border-color: var(--color-accent);
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        text-decoration: none !important;
    }

    .btn-elegant-outline {
        background-color: transparent;
        color: var(--color-light) !important;
        font-family: var(--font-sans);
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
        padding: 12px 30px;
        border-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: var(--transition-smooth);
        text-decoration: none !important;
    }

    .btn-elegant-outline:hover {
        background-color: var(--color-light);
        color: var(--color-dark) !important;
        border-color: var(--color-light);
        text-decoration: none !important;
    }

    /* Custom Navigation */
    .navbar {
        background-color: rgba(30, 18, 15, 0.95) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
        transition: var(--transition-smooth);
        padding-top: calc(15px + env(safe-area-inset-top, 0px)); /* Jarak aman dari notch/poni hp */
        padding-bottom: 15px;
    }

    .navbar-brand span {
        font-family: var(--font-serif);
        font-size: 1.8rem;
        letter-spacing: 1px;
        text-decoration: none !important;
    }

    .nav-link {
        font-family: var(--font-sans);
        font-weight: 500;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        color: rgba(253, 251, 247, 0.8) !important;
        margin: 0 10px;
        transition: var(--transition-smooth);
        position: relative;
        text-decoration: none !important;
        cursor: pointer;
    }

    .nav-link:hover, .nav-link.active {
        color: var(--color-accent) !important;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: -2px;
        left: 50%;
        background-color: var(--color-accent);
        transition: var(--transition-smooth);
        transform: translateX(-50%);
    }

    .nav-link:hover::after, .nav-link.active::after {
        width: 80%;
    }

    /* Disable default Bootstrap dropdown caret to avoid duplicate/weird ugly lines */
    .dropdown-toggle::after {
        display: none !important;
    }

    /* Custom Hover Dropdown for Desktop Menu */
    .dropdown-menu {
        display: none;
        background-color: var(--color-dark) !important;
        border: 1px solid rgba(212, 175, 55, 0.3) !important;
        border-radius: 6px;
        margin-top: 10px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        padding: 8px 0;
        position: absolute;
        left: 0;
        z-index: 1000;
    }

    @media (min-width: 992px) {
        .dropdown-menu {
            display: block !important;
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: opacity 0.35s cubic-bezier(0.25, 1, 0.5, 1), transform 0.35s cubic-bezier(0.25, 1, 0.5, 1), visibility 0.35s;
            pointer-events: none;
        }

        /* Triggers only when hovering the parent li item on desktop screens */
        .nav-item.dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
    }

    .dropdown-item {
        color: rgba(253, 251, 247, 0.8) !important;
        font-family: var(--font-sans);
        font-size: 0.9rem;
        padding: 10px 20px;
        transition: var(--transition-smooth);
        text-decoration: none !important;
        display: block;
    }

    .dropdown-item:hover {
        background-color: var(--color-primary) !important;
        color: var(--color-accent) !important;
        text-decoration: none !important;
        padding-left: 25px;
    }

    @media (min-width: 992px) {
        .dropdown-menu {
            display: block !important;
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: opacity 0.35s cubic-bezier(0.25, 1, 0.5, 1), transform 0.35s cubic-bezier(0.25, 1, 0.5, 1), visibility 0.35s;
            pointer-events: none;
            margin-top: 0 !important; /* Menghapus margin fisik bawaan di desktop */
        }

        /* Membuat jembatan hover transparan agar menu tidak tertutup saat kursor bergerak turun */
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -25px;
            left: 0;
            right: 0;
            height: 25px;
            background: transparent;
        }

        /* Triggers only when hovering the parent li item on desktop screens */
        .nav-item.dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(6px); /* Jarak mengambang yang pas dan aman */
            pointer-events: auto;
        }
    }

    .navbar-toggler {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        padding: 6px 10px;
    }

    .navbar-toggler:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    /* Custom Offcanvas Drawer Styling */
    .drawer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(30, 18, 15, 0.7);
        backdrop-filter: blur(4px);
        z-index: 1040;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.4s ease, visibility 0.4s ease;
    }

    .drawer-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .offcanvas-drawer {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        height: 100vh;
        background-color: var(--color-dark);
        border-left: 1px solid rgba(212, 175, 55, 0.2);
        z-index: 1050;
        box-shadow: -10px 0 30px rgba(0,0,0,0.5);
        transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        padding: 30px 24px;
    }

    .offcanvas-drawer.show {
        transform: translateX(-320px);
    }

    .drawer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .drawer-close {
        background: none;
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: var(--color-accent);
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .drawer-close:hover {
        background: var(--color-accent);
        color: var(--color-dark);
        border-color: var(--color-accent);
    }

    .drawer-nav {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .drawer-link {
        font-family: var(--font-sans);
        font-size: 1.1rem;
        font-weight: 500;
        color: rgba(253, 251, 247, 0.85);
        text-decoration: none !important;
        transition: var(--transition-smooth);
        padding: 8px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .drawer-link:hover, .drawer-link.active {
        color: var(--color-accent);
        padding-left: 8px;
    }

    /* Drawer Nested Dropdown Submenu */
    .drawer-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease-out;
        padding-left: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        border-left: 1px solid rgba(212, 175, 55, 0.15);
        margin-top: -5px;
        margin-bottom: 5px;
    }

    .drawer-submenu.show {
        max-height: 250px;
        margin-top: 5px;
        margin-bottom: 10px;
    }

    .drawer-sublink {
        font-family: var(--font-sans);
        font-size: 0.95rem;
        color: rgba(253, 251, 247, 0.65);
        text-decoration: none !important;
        transition: var(--transition-smooth);
        padding: 6px 0;
    }

    .drawer-sublink:hover {
        color: var(--color-accent);
        padding-left: 5px;
    }

    /* Custom Bulletproof Carousel Fade Engine */
    .hero-carousel {
        position: relative;
        background-color: var(--color-dark);
        height: 90vh;
        min-height: 550px;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .hero-carousel {
            height: 80vh;
        }
    }

    .carousel-inner-custom {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .carousel-item-custom {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        visibility: hidden;
        z-index: 1;
        transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), visibility 1.2s;
        pointer-events: none;
    }

    .carousel-item-custom.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
        pointer-events: auto;
    }

    .carousel-item-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(30, 18, 15, 0.5), rgba(30, 18, 15, 0.85));
    }

    .carousel-caption-custom {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 800px;
        z-index: 10;
        text-align: center;
        color: #fff;
    }

    .hero-title {
        font-size: 4rem;
        line-height: 1.15;
        margin-bottom: 20px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.3rem;
        }
    }

    /* Custom Carousel Controls */
    .carousel-control-custom {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 15;
        background: rgba(30, 18, 15, 0.4);
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: var(--color-accent);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .carousel-control-custom:hover {
        background: var(--color-accent);
        color: var(--color-dark);
        border-color: var(--color-accent);
    }

    .carousel-control-prev-custom { left: 20px; }
    .carousel-control-next-custom { right: 20px; }

    @media (max-width: 576px) {
        .carousel-control-custom { display: none; }
    }

    /* Custom Carousel Indicators */
    .carousel-indicators-custom {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 15;
        display: flex;
        gap: 10px;
    }

    .carousel-indicators-custom button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid var(--color-accent);
        background: transparent;
        cursor: pointer;
        padding: 0;
        transition: var(--transition-smooth);
    }

    .carousel-indicators-custom button.active {
        background: var(--color-accent);
        width: 30px;
        border-radius: 10px;
    }

    /* Decorative components */
    .divider {
        width: 80px;
        height: 2px;
        background-color: var(--color-accent);
        margin: 20px auto;
    }

    .divider-left {
        width: 60px;
        height: 2px;
        background-color: var(--color-accent);
        margin: 15px 0;
    }

    /* Feature Grid Cards */
    .feature-card {
        background-color: #fff;
        border: 1px solid rgba(93, 64, 55, 0.1);
        border-radius: 12px;
        padding: 40px 30px;
        transition: var(--transition-smooth);
        height: 100%;
        box-shadow: 0 10px 30px rgba(62, 39, 35, 0.02);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 0;
        background-color: var(--color-accent);
        transition: var(--transition-smooth);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 45px rgba(62, 39, 35, 0.08);
        border-color: rgba(212, 175, 55, 0.3);
    }

    .feature-card:hover::before {
        height: 100%;
    }

    .feature-icon-wrapper {
        width: 70px;
        height: 70px;
        background-color: rgba(212, 175, 55, 0.1);
        color: var(--color-accent);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 25px;
        transition: var(--transition-smooth);
    }

    .feature-card:hover .feature-icon-wrapper {
        background-color: var(--color-primary);
        color: var(--color-accent);
        transform: scale(1.05);
    }

    .feature-list {
        padding-left: 0;
        list-style: none;
        margin-top: 20px;
        font-size: 0.9rem;
    }

    .feature-list li {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        color: var(--color-secondary);
    }

    .feature-list li i {
        color: var(--color-accent);
        font-size: 0.8rem;
        margin-right: 10px;
    }

    /* Menu Section Custom Styling */
    .menu-item-box {
        padding: 15px;
        border-bottom: 1px dashed rgba(93, 64, 55, 0.2);
        transition: var(--transition-smooth);
    }

    .menu-item-box:hover {
        background-color: rgba(212, 175, 55, 0.05);
        border-bottom-style: solid;
    }

    .menu-price {
        font-family: var(--font-serif);
        font-size: 1.25rem;
        color: var(--color-accent);
        font-weight: bold;
    }

    /* Testimonials / Quote Section */
    .quote-section {
        background-color: var(--color-dark);
        color: var(--color-light);
        background-image: linear-gradient(rgba(30, 18, 15, 0.95), rgba(30, 18, 15, 0.95)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        border-top: 2px solid var(--color-accent);
        border-bottom: 2px solid var(--color-accent);
    }

    /* Promo section styling */
    .promo-card {
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--color-dark), var(--color-primary));
        color: var(--color-light);
        overflow: hidden;
        position: relative;
        z-index: 1;
        transition: var(--transition-smooth);
    }

    .promo-card::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(212, 175, 55, 0.05);
        border-radius: 50%;
        bottom: -50px;
        right: -50px;
        z-index: -1;
    }

    .promo-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45);
    }

    .promo-img-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
        border-bottom: 2px solid var(--color-accent);
    }

    .promo-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-smooth);
    }

    .promo-card:hover .promo-img-wrapper img {
        transform: scale(1.08);
    }

    .promo-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 5;
        font-size: 0.8rem;
        padding: 8px 14px;
        border-radius: 4px;
        background-color: var(--color-accent) !important;
        color: var(--color-dark) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* Form Custom Design */
    .form-control, .form-select {
        background-color: rgba(253, 251, 247, 0.5);
        border: 1px solid rgba(93, 64, 55, 0.2);
        padding: 12px 15px;
        border-radius: 4px;
        transition: var(--transition-smooth);
        color: var(--color-dark);
    }

    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: var(--color-accent);
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.15);
        outline: none;
    }

    /* Map & Info Wrapper */
    .map-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(0,0,0,0.05);
        border: 1px solid rgba(212, 175, 55, 0.2);
        height: 100%;
        min-height: 380px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        min-height: 380px;
        border: 0;
    }

    /* Scroll top button */
    .scroll-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 45px;
        height: 45px;
        background-color: var(--color-accent);
        color: var(--color-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition-smooth);
        z-index: 999;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .scroll-top.show {
        opacity: 1;
        visibility: visible;
    }

    .scroll-top:hover {
        background-color: var(--color-dark);
        color: var(--color-accent);
    }

    /* Notification Toast Container */
    .toast-container-custom {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 1000;
    }
    /* Custom Content Formatting */
    .content-formatted h1, .content-formatted h2, .content-formatted h3, .content-formatted h4 {
        color: var(--color-chocolate);
        font-family: var(--font-serif);
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    .content-formatted p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    .content-formatted a {
        color: var(--color-accent);
        text-decoration: underline;
    }
    .content-formatted ul, .content-formatted ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    .content-formatted img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }
</style>
