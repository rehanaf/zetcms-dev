<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Menu;
use App\Models\Redirect;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SearchLog;
use App\Models\PostView;
use App\Models\Comment;
use App\Services\ThemeService;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    protected function getMenus()
    {
        $relations = ['items.page', 'items.post', 'items.children.page', 'items.children.post'];
        return [
            'headerMenu' => Menu::with($relations)->find(\App\Models\Setting::get('menu_header')),
            'footerMenu' => Menu::with($relations)->find(\App\Models\Setting::get('menu_footer')),
            'sidebarMenu' => Menu::with($relations)->find(\App\Models\Setting::get('menu_sidebar')),
        ];
    }

    public function homepage()
    {
        $homepageId = \App\Models\Setting::get('homepage_page_id');
        
        if ($homepageId) {
            $page = Page::where('id', $homepageId)->published()->first();
        } else {
            // Cari halaman homepage yang dipublish (fallback)
            $page = Page::where('is_homepage', true)->published()->first();
        }

        if (!$page) {
            // Jika tidak ada homepage, coba tampilkan tulisan welcome default atau postingan terbaru
            $posts = Post::published()->latest()->take(3)->get();
            $menus = $this->getMenus();
            return view('welcome', array_merge([
                'posts' => $posts
            ], $menus));
        }

        $menus = $this->getMenus();
        $layoutView = ThemeService::layoutView('layouts.app');

        return view($layoutView, array_merge([
            'page' => $page,
            'model' => $page, // untuk seo-meta
        ], $menus));
    }

    public function pageShow($slug)
    {
        // Cari halaman statis
        $page = Page::where('slug', $slug)
            ->published()
            ->first();

        if (!$page) {
            // Cek redirect jika halaman tidak ditemukan
            $redirect = Redirect::where('old_url', $slug)
                ->orWhere('old_url', '/' . $slug)
                ->where('is_active', true)
                ->first();

            if ($redirect) {
                $redirect->increment('hits');
                return redirect($redirect->new_url, $redirect->type);
            }

            abort(404);
        }

        // Jika halaman ini diatur sebagai homepage, redirect ke root /
        $homepageId = \App\Models\Setting::get('homepage_page_id');
        if ($page->is_homepage || ($homepageId && $page->id == $homepageId)) {
            return redirect('/', 301);
        }

        $menus = $this->getMenus();
        $layoutView = ThemeService::layoutView('layouts.app');

        return view($layoutView, array_merge([
            'page' => $page,
            'model' => $page,
        ], $menus));
    }

    public function blogIndex(Request $request)
    {
        $query = Post::published()->latest();

        // Pencarian
        if ($request->has('q') && !empty($request->input('q'))) {
            $searchTerm = $request->input('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });

            // Log pencarian
            $posts = $query->paginate(10);
            SearchLog::create([
                'query' => $searchTerm,
                'results_count' => $posts->total(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
            ]);
        } else {
            $posts = $query->paginate(10);
        }

        // Filter Kategori
        if ($request->has('category')) {
            $categorySlug = $request->input('category');
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
                $posts = $query->paginate(10);
            }
        }

        // Filter Tag
        if ($request->has('tag')) {
            $tagSlug = $request->input('tag');
            $tag = Tag::where('slug', $tagSlug)->first();
            if ($tag) {
                $query->whereHas('tags', function($q) use ($tag) {
                    $q->where('tags.id', $tag->id);
                });
                $posts = $query->paginate(10);
            }
        }

        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        $menus = $this->getMenus();

        // Selesaikan view path dengan theme aktif
        $themeSlug = ThemeService::active()->slug;
        $viewPath = "themes.{$themeSlug}.blog.index";

        return view($viewPath, array_merge([
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            // dummy model for title
            'model' => (object) [
                'meta_title' => 'Blog - ' . \App\Models\Setting::get('site_name', config('app.name')),
                'meta_description' => 'Daftar artikel dan berita terbaru kami.',
                'og_image' => null,
            ],
        ], $menus));
    }

    public function blogShow($slug)
    {
        $post = Post::with('category', 'tags', 'comments')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment Views
        $post->increment('views');
        PostView::record($post->id);

        $menus = $this->getMenus();

        // Selesaikan view path dengan theme aktif
        $themeSlug = ThemeService::active()->slug;
        $viewPath = "themes.{$themeSlug}.blog.show";

        return view($viewPath, array_merge([
            'post' => $post,
            'model' => $post, // untuk seo-meta
        ], $menus));
    }

    public function storeComment(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->published()->firstOrFail();

        $validated = $request->validate([
            'author_name' => 'required_without:user_id|nullable|string|max:255',
            'author_email' => 'required_without:user_id|nullable|email|max:255',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->parent_id = $validated['parent_id'] ?? null;
        $comment->content = $validated['content'];
        
        if (auth()->check()) {
            $comment->user_id = auth()->id();
            $comment->author_name = auth()->user()->name;
            $comment->author_email = auth()->user()->email;
        } else {
            $comment->author_name  = $validated['author_name'];
            $comment->author_email = $validated['author_email'];

            // Simpan ke session agar form ter-pre-fill berikutnya
            session([
                'comment_author_name'  => $validated['author_name'],
                'comment_author_email' => $validated['author_email'],
            ]);
        }

        // Default status is 'pending'
        $comment->status = 'pending';
        $comment->save();

        return back()->with('success', 'Komentar Anda berhasil dikirim dan sedang menunggu moderasi.');
    }

    public function submitForm(Request $request, $slug)
    {
        $form = \App\Models\Form::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $rules = [];
        foreach ($form->fields as $field) {
            $rules[$field->name] = $field->is_required ? 'required' : 'nullable';
            if ($field->type === 'email') {
                $rules[$field->name] .= '|email';
            }
        }

        $validatedData = $request->validate($rules);

        $submission = \App\Models\FormSubmission::create([
            'form_id' => $form->id,
            'data' => $validatedData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $settings = is_string($form->settings) ? json_decode($form->settings, true) : $form->settings;
        if (is_array($settings) && !empty($settings['webhook_url'])) {
            try {
                $method = strtoupper($settings['webhook_method'] ?? 'POST');
                $headers = is_array($settings['webhook_headers'] ?? null) ? $settings['webhook_headers'] : [];
                $payload = [];

                if (isset($settings['webhook_payload']) && is_array($settings['webhook_payload'])) {
                    $customPayload = $settings['webhook_payload'];
                    array_walk_recursive($customPayload, function(&$item, $key) use ($validatedData) {
                        if (is_string($item)) {
                            foreach ($validatedData as $vk => $vv) {
                                $valStr = is_array($vv) ? implode(', ', $vv) : (string)$vv;
                                $item = str_replace("{{{$vk}}}", $valStr, $item);
                            }
                        }
                    });
                    $payload = $customPayload;
                } else {
                    $payload = [
                        'form_name' => $form->name,
                        'submission_id' => $submission->id,
                        'data' => $validatedData,
                        'ip_address' => $request->ip(),
                        'created_at' => $submission->created_at->toIso8601String(),
                    ];

                    if (!empty($settings['webhook_text_template'])) {
                        $text = $settings['webhook_text_template'];
                        foreach ($validatedData as $key => $value) {
                            $text = str_replace("{{{$key}}}", is_array($value) ? implode(', ', $value) : (string)$value, $text);
                        }
                        $payload['text'] = $text;
                        $payload['content'] = $text;
                    }
                }

                $req = \Illuminate\Support\Facades\Http::withHeaders($headers);
                if ($method === 'GET') {
                    $req->get($settings['webhook_url'], $payload);
                } elseif ($method === 'PUT') {
                    $req->put($settings['webhook_url'], $payload);
                } else {
                    $req->post($settings['webhook_url'], $payload);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Webhook failed for form ' . $form->name . ': ' . $e->getMessage());
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $form->success_message ?? 'Formulir berhasil dikirim!'
            ]);
        }

        return back()->with('form_success', $form->success_message ?? 'Formulir berhasil dikirim!');
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // In a real application, you would save this email to a database or send it to an API like Mailchimp
        // e.g., NewsletterSubscription::create(['email' => $request->email]);

        return back()->with('success', 'Terima kasih telah berlangganan newsletter kami!');
    }
}
