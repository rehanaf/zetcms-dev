<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&family=Onest:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
@php
    $activeTheme = \App\Models\Theme::active();
    $fontFamily = $activeTheme->settings['font_family'] ?? 'Inter';
    $colorGradient = $activeTheme->settings['color_gradient'] ?? 'cyber-blue';
@endphp
<style>
    :root {
        --font-primary: '{{ $fontFamily }}', sans-serif;
        
        /* Light Theme Defaults */
        --bg-main: #f8fafc;
        --bg-surface: #ffffff;
        --bg-surface-elevated: #f1f5f9;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        
        /* Gradients */
        @if($colorGradient == 'cyber-blue')
            --color-primary: #3b82f6;
            --gradient-main: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            --gradient-hover: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        @elseif($colorGradient == 'neon-green')
            --color-primary: #10b981;
            --gradient-main: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-hover: linear-gradient(135deg, #059669 0%, #047857 100%);
        @elseif($colorGradient == 'sunset-orange')
            --color-primary: #f97316;
            --gradient-main: linear-gradient(135deg, #f97316 0%, #e11d48 100%);
            --gradient-hover: linear-gradient(135deg, #ea580c 0%, #be123c 100%);
        @endif
        
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-bs-theme="dark"] {
        --bg-main: #0f172a;
        --bg-surface: #1e293b;
        --bg-surface-elevated: #334155;
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --border-color: #334155;
    }

    body {
        font-family: var(--font-primary);
        background-color: var(--bg-main);
        color: var(--text-main);
        transition: background-color 0.3s ease, color 0.3s ease;
        padding-top: 80px;
    }

    .navbar {
        background-color: var(--bg-surface) !important;
        border-bottom: 1px solid var(--border-color);
        backdrop-filter: blur(10px);
        transition: var(--transition-smooth);
    }
    
    .navbar-brand, .nav-link {
        color: var(--text-main) !important;
    }

    .nav-link:hover {
        background: var(--gradient-main);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .btn-gradient {
        background: var(--gradient-main);
        color: white !important;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .btn-gradient:hover {
        background: var(--gradient-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .card, .feature-card {
        background-color: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        transition: var(--transition-smooth);
        color: var(--text-main);
    }

    .card:hover, .feature-card:hover {
        transform: translateY(-5px);
        border-color: var(--color-primary);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .text-gradient {
        background: var(--gradient-main);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }

    /* Floating Theme Toggle */
    .theme-toggle {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--bg-surface-elevated);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: var(--transition-smooth);
    }
    
    .theme-toggle:hover {
        transform: scale(1.1);
        border-color: var(--color-primary);
    }
    
    /* SweetAlert Dark Mode fixes */
    [data-bs-theme="dark"] .swal2-popup {
        background: var(--bg-surface) !important;
        color: var(--text-main) !important;
    }
    [data-bs-theme="dark"] .swal2-title, [data-bs-theme="dark"] .swal2-html-container {
        color: var(--text-main) !important;
    }
</style>
