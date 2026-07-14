<?php

namespace App\Notifications;

use App\Models\Approval;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentSubmittedForReview extends Notification
{
    use Queueable;

    public function __construct(protected Approval $approval)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $subject = $this->approval->approvable;

        return [
            'title'   => 'Konten menunggu review',
            'message' => sprintf(
                '%s mengirim "%s" untuk direview.',
                $this->approval->submitter->name ?? 'Seseorang',
                $subject->title ?? 'Konten'
            ),
            'approval_id'     => $this->approval->id,
            'approvable_type' => $this->approval->approvable_type,
            'approvable_id'   => $this->approval->approvable_id,
        ];
    }
}
