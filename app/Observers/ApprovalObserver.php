<?php

namespace App\Observers;

use App\Models\Approval;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ContentReviewResult;
use App\Notifications\ContentSubmittedForReview;

class ApprovalObserver
{
    // Saat Editor submit konten untuk direview -> notifikasi ke semua user dengan role Admin
    public function created(Approval $approval): void
    {
        $reviewers = User::whereHas('role', fn ($q) => $q->whereIn('slug', ['admin', 'super-admin']))
            ->get();

        foreach ($reviewers as $reviewer) {
            $reviewer->notify(new ContentSubmittedForReview($approval));
        }
    }

    // Saat Admin approve/reject -> notifikasi ke submitter + update status konten aslinya
    public function updated(Approval $approval): void
    {
        if (! $approval->wasChanged('status') || $approval->status === 'pending') {
            return;
        }

        $approval->submitter?->notify(new ContentReviewResult($approval));

        $content = $approval->approvable;

        if (! $content) {
            return;
        }

        if ($approval->status === 'approved') {
            $content->update([
                'status'       => 'published',
                'published_at' => $content->published_at ?? now(),
            ]);
        } elseif ($approval->status === 'rejected') {
            $content->update(['status' => 'draft']);
        }
    }
}
