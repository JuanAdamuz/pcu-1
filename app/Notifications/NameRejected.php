<?php

namespace App\Notifications;

use App\Name;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NameRejected extends Notification implements ShouldQueue
{
    use Queueable;

    private $name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
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
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if ($this->name->type == 'imported') {
            return [
                'icon' => 'account_circle',
                'title' => 'Nombre rechazado',
                'message' => 'Tu antiguo nombre (' . $this->name->name.') ha sido considerado inapropiado por varios moderadores. Antes de jugar, tendrás que escoger otro.',
                'official' => true,
            ];
        }
        if ($this->name->type == 'change') {
            return [
                'icon' => 'account_circle',
                'title' => 'Cambio de nombre rechazado',
                'message' => 'El nombre que has escogido (' . $this->name->name.') ha sido considerado inapropiado por varios moderadores. Mantendrás tu nombre anterior.',
                'official' => true,
            ];
        }
        return [
            'icon' => 'account_circle',
            'title' => 'Nombre rechazado',
            'message' => 'El nombre que habías escogido (' . $this->name->name.') ha sido rechazado al considerarse inaceptable por varios moderadores. Tienes una única oportunidad más para elegir. Te recomendamos que uses el generador para hacerte una idea de los nombres que se esperan y permiten en el juego.',
            'official' => true,
        ];
    }
}
