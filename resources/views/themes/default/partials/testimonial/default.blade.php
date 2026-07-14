{{--
    Testimonial — Default Variant
    Fields: title, description, subtitle,
            source: 'dynamic' | 'manual'
            -- dynamic: testimonial_ids[], limit
            -- manual: manual_testimonials[]: name, role, company, content, avatar_id, rating
--}}
<section class="testimonial testimonial--default py-20 bg-white">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['subtitle']))
            <div class="text-center mb-14">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-500 text-lg max-w-xl mx-auto mb-2">{{ $data['description'] }}</p>
                @endif
                @if(!empty($data['subtitle']))
                    <p class="text-slate-400 text-sm">{{ $data['subtitle'] }}</p>
                @endif
            </div>
        @endif

        {{-- Collect testimonials --}}
        @php
            $testimonials = collect();
            if (($data['source'] ?? 'dynamic') === 'dynamic') {
                $query = \App\Models\Testimonial::where('is_active', true);
                if (!empty($data['testimonial_ids'])) {
                    $query->whereIn('id', $data['testimonial_ids']);
                }
                $limit = (int) ($data['limit'] ?? 6);
                $testimonials = $query->limit($limit)->get();
            } else {
                $testimonials = collect($data['manual_testimonials'] ?? []);
            }
        @endphp

        @if($testimonials->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonials as $item)
                    @php
                        $isModel = $item instanceof \Illuminate\Database\Eloquent\Model;
                        $name    = $isModel ? $item->name    : ($item['name'] ?? '');
                        $role    = $isModel ? $item->role    : ($item['role'] ?? '');
                        $company = $isModel ? $item->company : ($item['company'] ?? '');
                        $text    = $isModel ? $item->content : ($item['content'] ?? '');
                        $rating  = $isModel ? $item->rating  : ($item['rating'] ?? 5);
                        $avatarId = $isModel ? $item->avatar_id : ($item['avatar_id'] ?? null);
                        $avatar   = $avatarId ? \App\Models\Media::find($avatarId) : null;
                    @endphp
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-7">
                        {{-- Rating bintang --}}
                        <div class="flex gap-1 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-slate-200' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        <p class="text-slate-600 text-sm leading-relaxed mb-6 italic">"{{ $text }}"</p>

                        <div class="flex items-center gap-3">
                            @if($avatar)
                                <img src="{{ $avatar->url }}" alt="{{ $name }}"
                                     class="w-10 h-10 rounded-full object-cover shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                    <span class="text-indigo-600 font-bold text-sm">{{ strtoupper(substr($name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-slate-800 text-sm">{{ $name }}</p>
                                <p class="text-slate-400 text-xs">
                                    {{ implode(', ', array_filter([$role, $company])) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
