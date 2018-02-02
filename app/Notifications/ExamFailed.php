<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ExamFailed extends Notification implements ShouldQueue
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
        $url = route('setup-rules');

        return (new MailMessage)
                    ->subject('Has suspendido la prueba')
                    ->error()
                    ->greeting('Tenemos malas noticias')
                    ->line('Lamentamos informarte que no has pasado la prueba escrita.')
                    ->line('Te animamos a volverlo a intentar después de que te leas las normas un par de veces más:')
                    ->action('Ver nornas', $url)
                    ->line('No te desanimes, ¡tú puedes! Si quieres ayuda o tienes alguna duda no dudes en preguntarnos.');
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
            'title' => 'No has pasado la prueba',
            'message' => 'Lamentamos informarte de que no has aprobado. Inténtalo de nuevo después de haberte repasado las normas a fondo. Cualquier duda, pregúntanos, estaremos encantados de orientarte.',
            'url' => route('setup-rules'),
            'button_text' => 'Revisar las normas'
        ];
    }
}
