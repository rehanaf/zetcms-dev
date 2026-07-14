<header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        {{-- Brand Logo --}}
        <a href="{{ url('/') }}" class="font-extrabold text-xl tracking-tight text-slate-900 flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center text-white text-base font-black shadow-sm">
                Z
            </div>
            <span>{{ \App\Models\Setting::get('site_name', config('app.name')) }}</span>
        </a>

        {{-- Navigation Menu --}}
        <nav class="flex items-center gap-6">
            <ul class="flex items-center gap-6">
                @foreach($headerMenu->items ?? [] as $item)
                    <li class="relative group">
                        <a href="{{ $item->resolved_url }}"
                           target="{{ $item->target }}"
                           class="text-sm font-semibold text-slate-600 hover:text-amber-600 transition-colors flex items-center gap-1">
                            {{ $item->title }}
                            @if($item->children->isNotEmpty())
                                <svg class="w-3.5 h-3.5 mt-0.5 opacity-60 group-hover:rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            @endif
                        </a>

                        {{-- Dropdown sub-menu --}}
                        @if($item->children->isNotEmpty())
                            <ul class="absolute left-0 top-full mt-2 hidden group-hover:block bg-white border border-slate-100 rounded-xl shadow-xl min-w-[200px] z-50 py-1.5">
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ $child->resolved_url }}"
                                           target="{{ $child->target }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            @if($child->icon)
                                                <span class="text-base">{{ $child->icon }}</span>
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
            <a href="{{ url('/admin') }}" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-lg transition-all ml-4 shadow-sm">
                Admin Panel
            </a>
        </nav>
    </div>
</header>
