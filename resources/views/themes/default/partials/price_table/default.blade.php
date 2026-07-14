{{--
    Price Table (Manual) — Default Variant
    Fields: title, description,
            categories[]:
                title, icon, image_id
                items[]: name, price, description
--}}
<section class="price-table price-table--default py-20 bg-white">
    <div class="container mx-auto px-6 max-w-4xl">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-14">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-500 text-lg max-w-xl mx-auto">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['categories']))
            <div class="space-y-12">
                @foreach($data['categories'] as $category)
                    <div>
                        {{-- Judul kategori --}}
                        <div class="flex items-center gap-3 mb-6 pb-3 border-b border-slate-200">
                            @if(!empty($category['image_id']))
                                @php $catImg = \App\Models\Media::find($category['image_id']); @endphp
                                @if($catImg)
                                    <img src="{{ $catImg->url }}" alt="{{ $category['title'] ?? '' }}"
                                         class="w-10 h-10 object-contain rounded-lg">
                                @endif
                            @elseif(!empty($category['icon']))
                                <span class="text-3xl">{{ $category['icon'] }}</span>
                            @endif
                            <h3 class="text-xl font-bold text-slate-800">
                                {{ $category['title'] ?? '' }}
                            </h3>
                        </div>

                        {{-- Daftar item --}}
                        @if(!empty($category['items']))
                            <div class="space-y-4">
                                @foreach($category['items'] as $item)
                                    <div class="flex items-start justify-between gap-4 py-3 px-4 rounded-xl hover:bg-slate-50 transition">
                                        <div class="flex-1">
                                            <p class="font-semibold text-slate-800">{{ $item['name'] ?? '' }}</p>
                                            @if(!empty($item['description']))
                                                <p class="text-slate-500 text-sm mt-0.5">{{ $item['description'] }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="text-lg font-bold text-indigo-700">
                                                {{ $item['price'] ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
