<?php

namespace App\Providers;

use App\Models\Approval;
use App\Models\FormSubmission;
use App\Models\Page;
use App\Models\Post;
use App\Observers\ApprovalObserver;
use App\Observers\FormSubmissionObserver;
use App\Observers\PageObserver;
use App\Observers\PostObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Override APP_URL dari tabel settings jika tersedia
        try {
            $appUrl = \App\Models\Setting::get('app_url');
            if ($appUrl) {
                \Illuminate\Support\Facades\Config::set('app.url', $appUrl);
                \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
                
                if (str_starts_with($appUrl, 'https://')) {
                    \Illuminate\Support\Facades\URL::forceScheme('https');
                }
            }
        } catch (\Throwable $th) {
            // Abaikan jika tabel settings belum ada (contoh: saat artisan migrate pertama kali)
        }

        // Observer konten: auto activity log + auto revision setiap create/update/delete
        Post::observe(PostObserver::class);
        Page::observe(PageObserver::class);

        // Observer approval workflow: notifikasi otomatis + update status konten
        Approval::observe(ApprovalObserver::class);

        // Observer form builder: notifikasi otomatis ke admin saat ada submission baru
        FormSubmission::observe(FormSubmissionObserver::class);
    }
}
