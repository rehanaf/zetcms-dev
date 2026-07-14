{{--
    Text Editor — Default Variant
    Fields: content (rich text / HTML)
--}}
<section class="texteditor texteditor--default py-16 bg-white">
    <div class="container mx-auto px-6 max-w-3xl">
        @if(!empty($data['content']))
            <div class="prose prose-slate prose-lg max-w-none
                        prose-headings:font-bold prose-headings:text-slate-800
                        prose-a:text-indigo-600 prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-xl prose-img:shadow-md">
                {!! $data['content'] !!}
            </div>
        @endif
    </div>
</section>
