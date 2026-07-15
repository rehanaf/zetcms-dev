{{--
    FAQ — Elegant Variant
    Fields: title, description, subtitle,
            faqs[]: question, answer
--}}
<section class="py-5 bg-white">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['subtitle']))
                    <p class="text-primary text-gradient fw-bold m-0" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1 font-serif">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
                @if(!empty($data['description']))
                    <p class="text-muted mt-3 font-sans">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        {{-- Accordion FAQ --}}
        @if(!empty($data['faqs']))
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        @foreach($data['faqs'] as $i => $faq)
                            <div class="accordion-item border-0 mb-3 rounded shadow-sm bg-main">
                                <h2 class="accordion-header" id="heading{{ $i }}">
                                    <button class="accordion-button collapsed bg-transparent font-serif text-primary fw-bold fs-5 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}" aria-expanded="false" aria-controls="collapse{{ $i }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $i }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $i }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body font-sans text-secondary border-top border-accent border-opacity-25 pt-4">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
