<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewPassed extends Notification implements ShouldQueue
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
        $url = route('home');

        return (new MailMessage())
            ->subject('Certificado')
            ->greeting('Certificado')
            ->line('Has terminado el proceso de certificación.')
            ->line('Ya eres un jugador de pleno derecho. ¡Enhorabuena!')
            ->action('Empezar a jugar', $url);
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
        return [
            'icon'    => 'check_circle',
            'title'   => 'Certificado',
            'message' => '¡Has obtenido el certificado! Ahora ya puedes jugar a POPLife. ¡Te damos la bienvenida!',
        ];
    }
}
