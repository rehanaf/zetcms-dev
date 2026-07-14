<?php

namespace App\Notifications;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFormSubmission extends Notification
{
    use Queueable;

    public function __construct(protected FormSubmission $submission)
    {
    }

    /**
     * Kirim ke database (in-app) selalu. Kirim ke mail juga kalau form ini
     * punya notification_email yang diisi.
     */
    public function via($notifiable): array
    {
        $channels = ['database'];

        if (! empty($this->submission->form->notification_email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Submission baru: ' . $this->submission->form->name,
            'message' => 'Ada pengisian form baru yang perlu ditinjau.',
            'form_id'       => $this->submission->form_id,
            'submission_id' => $this->submission->id,
        ];
    }

    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Submission Baru: ' . $this->submission->form->name)
            ->line('Ada pengisian form baru pada "' . $this->submission->form->name . '".')
            ->line('Data: ' . json_encode($this->submission->data, JSON_UNESCAPED_UNICODE))
            ->action('Lihat Submission', url('/admin/forms/' . $this->submission->form_id . '/submissions'));
    }
}
