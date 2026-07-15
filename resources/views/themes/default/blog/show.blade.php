@extends('themes.default.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Post Header -->
    <div class="space-y-4">
        @if($post->category)
            <a href="{{ route('category.show', $post->category->slug) }}" class="inline-flex bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                {{ $post->category->name }}
            </a>
        @endif

        <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight">
            {{ $post->title }}
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-700">
                    {{ substr($post->user->name ?? 'A', 0, 1) }}
                </div>
                <span class="font-semibold text-slate-700">{{ $post->user->name ?? 'Administrator' }}</span>
            </div>
            <span>•</span>
            <span>{{ $post->published_at ? $post->published_at->format('d M Y, H:i') : $post->created_at->format('d M Y, H:i') }}</span>
            <span>•</span>
            <span>{{ $post->views }} Views</span>
        </div>
    </div>

    <!-- Featured Image -->
    @if($post->featured_image)
        <div class="rounded-2xl overflow-hidden shadow-md max-h-[480px]">
            <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="object-cover w-full h-full">
        </div>
    @endif

    <!-- Content -->
    <article class="prose prose-slate lg:prose-lg max-w-none">
        {!! $post->content !!}
    </article>

    <!-- Tags -->
    @if($post->tags->isNotEmpty())
        <div class="flex flex-wrap gap-2 pt-6 border-t border-slate-100">
            @foreach($post->tags as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}" class="bg-slate-100 hover:bg-amber-100 hover:text-amber-700 px-3 py-1 rounded-lg text-xs font-semibold text-slate-600 transition-colors">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Related Posts -->
    @if($post->relatedPosts->isNotEmpty())
        <div class="pt-10 border-t border-slate-100 space-y-6">
            <h3 class="text-2xl font-bold text-slate-900">Artikel Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($post->relatedPosts as $related)
                    <div class="flex gap-4 items-center bg-slate-50 p-4 rounded-xl hover:bg-slate-100 transition-colors">
                        @if($related->featured_image)
                            <img src="{{ asset($related->featured_image) }}" alt="{{ $related->title }}" class="h-16 w-16 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="h-16 w-16 rounded-lg bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-400 flex-shrink-0">
                                ZC
                            </div>
                        @endif
                        <div>
                            <h4 class="font-bold text-slate-900 line-clamp-1 hover:text-amber-500 transition-colors">
                                <a href="{{ route('page.show', $related->slug) }}">{{ $related->title }}</a>
                            </h4>
                            <p class="text-xs text-slate-500 mt-1">{{ $related->published_at ? $related->published_at->format('d M Y') : $related->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Comments Section -->
    <div class="pt-10 border-t border-slate-100 space-y-8">
        <h3 class="text-2xl font-bold text-slate-900">Komentar</h3>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Comments List -->
        @php
            $approvedComments = $post->comments()->where('status', 'approved')->whereNull('parent_id')->with('replies')->get();
        @endphp

        @if($approvedComments->isEmpty())
            <p class="text-slate-500 italic text-sm">Belum ada komentar. Jadilah yang pertama memberikan komentar!</p>
        @else
            <div class="space-y-6">
                @foreach($approvedComments as $comment)
                    <div class="bg-slate-50 p-5 rounded-2xl space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm">
                                    {{ substr($comment->author_name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-bold text-sm text-slate-900">{{ $comment->author_name }}</h5>
                                    <p class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <!-- Reply Button (set parent_id) -->
                            <button onclick="setParentId({{ $comment->id }}, '{{ $comment->author_name }}')" class="text-xs font-bold text-amber-600 hover:text-amber-700">Balas</button>
                        </div>
                        <p class="text-slate-700 text-sm pl-12">{{ $comment->content }}</p>

                        <!-- Replies -->
                        @if($comment->replies->isNotEmpty())
                            <div class="pl-12 space-y-4 border-l border-slate-200">
                                @foreach($comment->replies->where('status', 'approved') as $reply)
                                    <div class="bg-white p-4 rounded-xl border border-slate-100">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="h-7 w-7 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-700 text-xs">
                                                {{ substr($reply->author_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="font-bold text-xs text-slate-900">{{ $reply->author_name }}</h6>
                                                <p class="text-[10px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <p class="text-slate-700 text-xs">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Comment Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h4 class="font-bold text-lg text-slate-900" id="form-title">Tulis Komentar</h4>
            
            <div id="reply-banner" class="hidden flex items-center justify-between bg-amber-50 text-amber-800 px-4 py-2 rounded-lg text-xs font-semibold">
                <span>Membalas komentar dari <strong id="replying-to"></strong></span>
                <button onclick="clearParentId()" class="text-slate-400 hover:text-slate-600">Batal</button>
            </div>

            <form action="{{ route('blog.comment', $post->slug) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="parent_id" id="parent_id" value="">

                @guest
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-600 uppercase">Nama Lengkap</label>
                            <input
                                type="text"
                                name="author_name"
                                required
                                value="{{ old('author_name', session('comment_author_name')) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                                placeholder="Nama Anda"
                            />
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-600 uppercase">Alamat Email</label>
                            <input
                                type="email"
                                name="author_email"
                                required
                                value="{{ old('author_email', session('comment_author_email')) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                                placeholder="nama@email.com"
                            />
                        </div>
                    </div>
                @endguest

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-600 uppercase">Komentar</label>
                    <textarea 
                        name="content" 
                        rows="4" 
                        required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="Tulis pendapat Anda di sini..."
                    ></textarea>
                </div>

                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-md transition-colors">
                    Kirim Komentar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function setParentId(id, name) {
        document.getElementById('parent_id').value = id;
        document.getElementById('replying-to').innerText = name;
        document.getElementById('reply-banner').classList.remove('hidden');
        document.getElementById('form-title').scrollIntoView({ behavior: 'smooth' });
    }

    function clearParentId() {
        document.getElementById('parent_id').value = '';
        document.getElementById('reply-banner').classList.add('hidden');
    }
</script>
@endsection
