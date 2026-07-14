<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver extends ContentObserver
{
    // Override kalau Post butuh perilaku khusus di luar yang sudah ada di ContentObserver.
    // Contoh: saat post dihapus permanen (force delete), hapus juga statistik view hariannya.
    public function forceDeleted(Post $post): void
    {
        $post->dailyViews()->delete();
        $this->log($post, 'force_deleted');
    }
}
