<?php

namespace App\Traits;

use App\Models\Revision;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRevisions
{
    public function revisions(): MorphMany
    {
        return $this->morphMany(Revision::class, 'revisionable')->latest();
    }

    // Simpan snapshot kondisi model saat ini sebagai revisi baru
    public function saveRevision(?string $note = null): Revision
    {
        return $this->revisions()->create([
            'user_id' => auth()->id(),
            'data'    => $this->getAttributes(),
            'note'    => $note,
        ]);
    }

    // Kembalikan model ke kondisi pada revisi tertentu
    public function restoreRevision(Revision $revision): void
    {
        $this->fill($revision->data)->save();
    }
}
