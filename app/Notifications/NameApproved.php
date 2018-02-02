<?php

namespace App\Notifications;

use App\Name;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NameApproved extends Notification implements ShouldQueue
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
                'title' => 'Nombre aceptado',
                'message' => 'Enhorabuena, ' . $this->name->name . ': tu nuevo nombre ha sido revisado y aceptado. Si quieres cambiarlo una vez antes de empezar a jugar, puedes hacerlo desde ajustes. Asegúrate de cambiarlo en el foro y TS3.'
            ];
        }
        if ($this->name->type == 'changed') {
            if (!is_null($this->name->original_name)) {
                return [
                    'icon' => 'account_circle',
                    'title' => 'Nombre aceptado con cambios',
                    'message' => 'Enhorabuena, ' . $this->name->name . ': tu nombre ha sido revisado y aceptado, aunque hemos hecho algún cambio en él (antes era "' . $this->name->original_name .'"). Si crees que es un error ponte en contacto con nosotros. Asegúrate de cambiarlo en el foro y TS3. Si quieres cambiarlo una vez antes de empezar a jugar, puedes hacerlo desde ajustes.'
                ];
            }
            return [
                'icon' => 'account_circle',
                'title' => 'Nombre aceptado',
                'message' => 'Enhorabuena, ' . $this->name->name . ': tu nuevo nombre ha sido revisado y aceptado. Asegúrate de cambiarlo en el foro y TS3.'
            ];
        }

        if (!is_null($this->name->original_name)) {
            return [
                'icon' => 'account_circle',
                'title' => 'Nombre aceptado con cambios',
                'message' => 'Enhorabuena, ' . $this->name->name . ': hemos revisado y aceptado tu nombre, aunque hemos hecho algún cambio en él (antes era "' . $this->name->original_name .'"). Si crees que es un error ponte en contacto con nosotros. Asegúrate de cambiarlo en el foro y TS3.'
            ];
        }

        return [
            'icon' => 'account_circle',
            'title' => 'Nombre aceptado',
            'message' => 'Enhorabuena, ' . $this->name->name . ': hemos revisado y aceptado tu nombre. Asegúrate de cambiarlo en el foro y TS3.'
        ];
    }
}
