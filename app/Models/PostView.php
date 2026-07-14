<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostView extends Model
{
    protected $fillable = ['post_id', 'viewed_date', 'views_count'];

    protected $casts = ['viewed_date' => 'date'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Increment (atau buat baru) hitungan view untuk hari ini
    public static function record(int $postId): void
    {
        try {
            $record = static::firstOrCreate(
                ['post_id' => $postId, 'viewed_date' => now()->toDateString()],
                ['views_count' => 0]
            );
            $record->increment('views_count');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Race condition: baris sudah dibuat oleh request lain — langsung increment
            static::where('post_id', $postId)
                ->where('viewed_date', now()->toDateString())
                ->increment('views_count');
        }
    }
}
