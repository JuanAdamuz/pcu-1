<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamExpired extends Notification implements ShouldQueue
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
        $url = route('setup-exam');

        return (new MailMessage())
            ->subject('Tu aprobado ha expirado')
            ->error()
            ->greeting('Hacía tiempo que no te veíamos.')
            ->line('Lamentamos informarte que tu aprobado ha expirado.')
            ->line('Tendrás que repetir la prueba escrita, aunque sospechamos que no será un problema después de ver tu aprobado.')
            ->action('Repetir prueba', $url)
            ->line('No te desanimes. Piensa que una vez la pasaste. Revísate las normas por si hubieran cambiado.');
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
            'icon'        => 'alarm',
            'title'       => 'Tu aprobado ha expirado',
            'message'     => 'Como tu aprobado ha expirado, tendrás que volver a realizar la prueba escrita. No te desanimes, si ya la pasaste una vez no te costará mucho ahora.',
            'url'         => route('setup-exam'),
            'button_text' => 'Volver a intentar',
        ];
    }
}
