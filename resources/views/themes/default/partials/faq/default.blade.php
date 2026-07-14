{{--
    FAQ — Default Variant
    Fields: title, description, subtitle,
            faqs[]: question, answer
--}}
<section class="faq faq--default py-20 bg-white">
    <div class="container mx-auto px-6 max-w-3xl">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-14">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-500 text-lg mb-2">{{ $data['description'] }}</p>
                @endif
                @if(!empty($data['subtitle']))
                    <p class="text-slate-400 text-sm">{{ $data['subtitle'] }}</p>
                @endif
            </div>
        @endif

        {{-- Accordion FAQ --}}
        @if(!empty($data['faqs']))
            <div class="space-y-4" x-data="{ open: null }">
                @foreach($data['faqs'] as $i => $faq)
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        <button
                            class="w-full flex justify-between items-center text-left px-6 py-4 font-medium text-slate-800 hover:bg-slate-50 transition"
                            @click="open = open === {{ $i }} ? null : {{ $i }}"
                        >
                            <span>{{ $faq['question'] }}</span>
                            <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200"
                                 :class="{ 'rotate-180': open === {{ $i }} }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open === {{ $i }}" x-collapse
                             class="px-6 py-4 bg-slate-50 text-slate-600 text-sm leading-relaxed border-t border-slate-100">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
