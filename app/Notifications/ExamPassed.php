<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamPassed extends Notification implements ShouldQueue
{
    use Queueable;

    private $exam;

    /**
     * Create a new notification instance.
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
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
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('setup-forum');
        $date = $this->exam->expires_at->setTimezone($this->exam->user->timezone)->format('d/m/Y \a \l\a\s h:i');

        return (new MailMessage())
                ->subject('Has aprobado. ¡A por lo siguiente!')
                ->greeting('¡Enhorabuena!')
                ->line('Has aprobado la prueba escrita.')
                ->line('¡Ya queda poco! Entra a la página para seguir con el proceso.')
                ->action('Siguiente paso', $url)
                ->line('Recuerda que tienes hasta el '.$date.' para terminar el proceso.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        $date = $this->exam->expires_at->setTimezone($this->exam->user->timezone)->format('d/m/Y \a \l\a\s h:i');

        return [
            'icon'    => 'done',
            'title'   => '¡Has aprobado!',
            'message' => 'Enhorabuena, has aprobado la prueba escrita. ¡Ya casi no queda nada! Ahora, a por la entrevista. Tienes hasta el '.$date.' para pasarla.',
        ];
    }
}
