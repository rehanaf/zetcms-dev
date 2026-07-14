<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Observer dasar yang dipakai bareng oleh PostObserver & PageObserver
 * (dan model lain nanti kalau perlu perilaku sama: audit log otomatis + revisi otomatis).
 */
abstract class ContentObserver
{
    public function saving(Model $model): void
    {
        if (isset($model->status) && $model->status === 'published' && empty($model->published_at)) {
            $model->published_at = now();
        }
    }

    public function created(Model $model): void
    {
        $this->log($model, 'created');

        // Revisi pertama = kondisi awal saat konten dibuat
        if (method_exists($model, 'saveRevision')) {
            $model->saveRevision('Konten dibuat');
        }
    }

    public function updated(Model $model): void
    {
        // Jangan bikin revisi/log untuk perubahan sepele yang otomatis (mis. soft delete/restore
        // sudah ditangani event lain, atau update views counter yang terlalu sering)
        $ignored = ['views', 'updated_at'];
        $changed = array_diff(array_keys($model->getChanges()), $ignored);

        if (empty($changed)) {
            return;
        }

        $this->log($model, 'updated', [
            'before' => array_intersect_key($model->getOriginal(), array_flip($changed)),
            'after'  => array_intersect_key($model->getAttributes(), array_flip($changed)),
        ]);

        if (method_exists($model, 'saveRevision')) {
            $model->saveRevision('Diperbarui: ' . implode(', ', $changed));
        }

        // Kalau status berubah jadi published, catat activity khusus (memudahkan filter log)
        if (in_array('status', $changed) && $model->status === 'published') {
            $this->log($model, 'published');
        }
    }

    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted');
    }

    public function restored(Model $model): void
    {
        $this->log($model, 'restored');
    }

    protected function log(Model $model, string $action, array $properties = []): void
    {
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'subject_type' => get_class($model),
            'subject_id'  => $model->getKey(),
            'properties'  => $properties ?: null,
            'ip_address'  => request()?->ip(),
            'user_agent'  => request()?->userAgent(),
        ]);
    }
}
