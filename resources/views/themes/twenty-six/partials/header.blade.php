<header class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center gap-2 fw-bold">
            @php
                $siteLogo = \App\Models\Setting::get('site_logo');
                $siteName = \App\Models\Setting::get('site_name', config('app.name'));
            @endphp
            
            @if($siteLogo)
                <img src="{{ Storage::disk('public')->url($siteLogo) }}" alt="{{ $siteName }}" style="height: 32px;">
            @endif
            
            <span>{{ $siteName }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @foreach($headerMenu->items ?? [] as $item)
                    <li class="nav-item {{ $item->children->isNotEmpty() ? 'dropdown' : '' }}">
                        <a href="{{ $item->resolved_url }}"
                           target="{{ $item->target }}"
                           class="nav-link {{ $item->children->isNotEmpty() ? 'dropdown-toggle' : '' }}"
                           @if($item->children->isNotEmpty()) role="button" data-bs-toggle="dropdown" aria-expanded="false" @endif>
                            {{ $item->title }}
                        </a>

                        @if($item->children->isNotEmpty())
                            <ul class="dropdown-menu">
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ $child->resolved_url }}" target="{{ $child->target }}" class="dropdown-item">
                                            @if($child->icon)
                                                <span class="me-2">{{ $child->icon }}</span>
                                            @endif
                                            {{ $child->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</header>