<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            @php
                $siteLogo = \App\Models\Setting::get('site_logo');
                $siteName = \App\Models\Setting::get('site_name', config('app.name', 'Dapoer Cendana'));
                $headerBtnText = \App\Models\Setting::get('header_button_text', 'Booking');
                $headerBtnUrl = \App\Models\Setting::get('header_button_url', '#kontak');
            @endphp
            @if($siteLogo)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($siteLogo) }}" alt="{{ $siteName }}" style="height: 40px; width: auto;" class="me-2">
            @else
                <span class="text-accent fw-bold m-0 pe-2">{{ $siteName }}</span>
            @endif
        </a>
        <button class="navbar-toggler" type="button" aria-label="Toggle navigation" id="drawerTrigger">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                @if(isset($headerMenu) && $headerMenu->items)
                    @foreach($headerMenu->items as $item)
                        @if($item->children->count() > 0)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="{{ $item->url }}" id="navbarDropdown{{ $loop->index }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $item->title }} <i class="fa-solid fa-chevron-down ms-1 fs-8 text-accent"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $loop->index }}">
                                    @foreach($item->children as $child)
                                        <li><a class="dropdown-item" href="{{ $child->url }}">{{ $child->title }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $item->url }}">{{ $item->title }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
                @if($headerBtnText)
                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <a class="btn-elegant py-2 px-4" href="{{ $headerBtnUrl }}">{{ $headerBtnText }}</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<!-- Offcanvas Drawer Mobile Menu -->
<div class="drawer-overlay" id="drawerOverlay"></div>
<div class="offcanvas-drawer" id="mobileDrawer">
    <div class="drawer-header">
        @if($siteLogo)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($siteLogo) }}" alt="{{ $siteName }}" style="height: 40px; width: auto;" class="m-0">
        @else
            <span class="text-accent fw-bold font-serif fs-4 m-0">{{ $siteName }}</span>
        @endif
        <button class="drawer-close" id="drawerClose" aria-label="Close menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="drawer-nav">
        @if(isset($headerMenu) && $headerMenu->items)
            @foreach($headerMenu->items as $item)
                @if($item->children->count() > 0)
                    <div>
                        <a class="drawer-link" id="drawerDropdownToggle{{ $loop->index }}">
                            {{ $item->title }} <i class="fa-solid fa-chevron-down fs-8 text-accent transition-transform" id="drawerChevron{{ $loop->index }}" style="transition: transform 0.3s ease;"></i>
                        </a>
                        <div class="drawer-submenu" id="drawerSubmenu{{ $loop->index }}">
                            @foreach($item->children as $child)
                                <a class="drawer-sublink" href="{{ $child->url }}">{{ $child->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a class="drawer-link" href="{{ $item->url }}">{{ $item->title }}</a>
                @endif
            @endforeach
        @endif
        
        @if($headerBtnText)
        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
            <a class="btn-elegant py-3 px-4 w-100 text-center d-block" href="{{ $headerBtnUrl }}">{{ $headerBtnText }}</a>
        </div>
        @endif
    </div>
</div>
