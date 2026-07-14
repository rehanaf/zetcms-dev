<?php

namespace App\Observers;

use App\Models\FormSubmission;
use App\Models\User;
use App\Notifications\NewFormSubmission;

class FormSubmissionObserver
{
    // Notifikasi ke semua Admin setiap ada submission baru masuk
    public function created(FormSubmission $submission): void
    {
        $admins = User::whereHas('role', fn ($q) => $q->whereIn('slug', ['admin', 'super-admin']))
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewFormSubmission($submission));
        }
    }
}
