{{--
    Menu — Default Variant
    Fields: description, menu_id
    Relasi ke Model: \App\Models\Menu
--}}
<nav class="menu menu--default bg-white border-b border-slate-100 shadow-sm">
    <div class="container mx-auto px-6">
        @php
            $menuModel = !empty($data['menu_id']) ? \App\Models\Menu::with('items.children')->find($data['menu_id']) : null;
        @endphp

        @if($menuModel && $menuModel->items->isNotEmpty())
            <ul class="flex flex-wrap items-center gap-1 py-3">
                @foreach($menuModel->items as $item)
                    <li class="relative group">
                        <a href="{{ $item->resolved_url }}"
                           target="{{ $item->target ?? '_self' }}"
                           class="inline-block px-4 py-2 text-sm font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                            {{ $item->title }}
                        </a>

                        {{-- Sub-menu (jika ada relasi children) --}}
                        @if($item->children && $item->children->isNotEmpty())
                            <ul class="absolute left-0 top-full mt-1 hidden group-hover:block bg-white border border-slate-100 rounded-xl shadow-lg min-w-[180px] z-50 py-1">
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ $child->resolved_url }}"
                                           target="{{ $child->target ?? '_self' }}"
                                           class="block px-4 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition">
                                            {{ $child->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @elseif(!empty($data['description']))
            <p class="text-slate-400 text-sm py-3">{{ $data['description'] }}</p>
        @endif
    </div>
</nav>
