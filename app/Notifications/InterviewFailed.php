<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InterviewFailed extends Notification implements ShouldQueue
{
    use Queueable;

    private $exam;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Si tiene activadas las notificaciones por correo electrónico
        if ($this->exam->user->email_verified && $this->exam->user->email_enabled) {
            return ['mail', 'database'];
        }
        // Si no, por base de datos.
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->exam->user->getExamTriesRemaining() == 0) {
            return (new MailMessage)
                ->subject('Has suspendido la entrevista')
                ->error()
                ->greeting('Tenemos malas noticias')
                ->line('Lamentamos informarte que has suspendido la entrevista personal.')
                ->line('Desgraciadamente, has agotado tus oportunidades al suspender tres veces seguidas, y no podrás jugar a POPLife.')
                ->line('Sintiéndolo mucho, nos despedimos.');
        }

        $url = route('setup-rules');

        return (new MailMessage)
            ->subject('Has suspendido la entrevista')
            ->error()
            ->greeting('Tenemos malas noticias')
            ->line('Lamentamos informarte que has suspendido la entrevista personal.')
            ->line('Tendrás que repetir la prueba escrita, y te recomendamos que revises las normas:')
            ->action('Ver nornas', $url)
            ->line('Si quieres ayuda o tienes alguna duda no dudes en preguntarnos. Ánimo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'icon' => 'sentiment_very_dissatisfied',
            'title' => 'Has suspendido la entrevista',
            'message' => 'Lamentamos informarte de que no has aprobado. Tendrás de volver a hacer la prueba escrita y la entrevista, te recomendamos repasarte lar normas. Cualquier duda, pregúntanos.',
            'url' => route('setup-rules'),
            'button_text' => 'Revisar las normas'
        ];
    }
}
