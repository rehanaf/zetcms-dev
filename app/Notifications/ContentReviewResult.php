<?php

namespace App\Notifications;

use App\Models\Approval;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentReviewResult extends Notification
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
            'title'   => $this->approval->status === 'approved'
                ? 'Konten Anda disetujui'
                : 'Konten Anda ditolak',
            'message' => sprintf(
                '"%s" telah %s%s',
                $subject->title ?? 'Konten',
                $this->approval->status === 'approved' ? 'disetujui' : 'ditolak',
                $this->approval->notes ? ': ' . $this->approval->notes : '.'
            ),
            'approval_id'    => $this->approval->id,
            'approvable_type' => $this->approval->approvable_type,
            'approvable_id'   => $this->approval->approvable_id,
            'status'          => $this->approval->status,
        ];
    }
}
