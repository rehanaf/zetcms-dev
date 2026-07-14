{{--
    Team Member — Default Variant
    Fields: title, description, subtitle,
            members[]: name, role, image_id, bio,
                       facebook_url, twitter_url, linkedin_url, instagram_url
--}}
<section class="team-member team-member--default py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
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

        {{-- Grid anggota --}}
        @if(!empty($data['members']))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($data['members'] as $member)
                    @php
                        $media = !empty($member['image_id']) ? \App\Models\Media::find($member['image_id']) : null;
                    @endphp
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden text-center p-6">
                        {{-- Avatar --}}
                        @if($media)
                            <img src="{{ $media->url }}" alt="{{ $member['name'] ?? '' }}"
                                 class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-indigo-50">
                        @else
                            <div class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                                <span class="text-indigo-600 font-bold text-3xl">
                                    {{ strtoupper(substr($member['name'] ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        @if(!empty($member['name']))
                            <h3 class="font-bold text-slate-800 text-lg">{{ $member['name'] }}</h3>
                        @endif
                        @if(!empty($member['role']))
                            <p class="text-indigo-600 text-sm mb-3">{{ $member['role'] }}</p>
                        @endif
                        @if(!empty($member['bio']))
                            <p class="text-slate-500 text-xs leading-relaxed mb-4">{{ $member['bio'] }}</p>
                        @endif

                        {{-- Sosial media --}}
                        <div class="flex justify-center gap-3">
                            @if(!empty($member['facebook_url']))
                                <a href="{{ $member['facebook_url'] }}" target="_blank" rel="noopener"
                                   class="text-slate-400 hover:text-blue-600 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                    </svg>
                                </a>
                            @endif
                            @if(!empty($member['twitter_url']))
                                <a href="{{ $member['twitter_url'] }}" target="_blank" rel="noopener"
                                   class="text-slate-400 hover:text-sky-500 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0022.43 1a9 9 0 01-2.88 1.1A4.52 4.52 0 0016.11 0C13.58 0 11.54 2.04 11.54 4.56c0 .36.04.7.11 1.03C7.69 5.4 4.07 3.58 1.64 0.74A4.55 4.55 0 001 2.96c0 1.58.8 2.97 2.02 3.79a4.5 4.5 0 01-2.05-.57v.06c0 2.2 1.57 4.04 3.65 4.46a4.56 4.56 0 01-2.04.08 4.53 4.53 0 004.23 3.14A9.07 9.07 0 010 15.54 12.8 12.8 0 006.92 18c8.3 0 12.85-6.88 12.85-12.85l-.01-.59A9.17 9.17 0 0023 3z"/>
                                    </svg>
                                </a>
                            @endif
                            @if(!empty($member['linkedin_url']))
                                <a href="{{ $member['linkedin_url'] }}" target="_blank" rel="noopener"
                                   class="text-slate-400 hover:text-blue-700 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2zm2-3a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </a>
                            @endif
                            @if(!empty($member['instagram_url']))
                                <a href="{{ $member['instagram_url'] }}" target="_blank" rel="noopener"
                                   class="text-slate-400 hover:text-pink-500 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                        <path fill="white" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
