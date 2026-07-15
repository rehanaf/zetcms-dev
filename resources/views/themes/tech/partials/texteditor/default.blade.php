{{--
    Text Editor — Default Variant
    Fields: content (rich text / HTML)
--}}
<section class="py-5 bg-white texteditor-block">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                @if(!empty($data['content']))
                    <div class="font-sans content-formatted">
                        {!! $data['content'] !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
