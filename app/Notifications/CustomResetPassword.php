<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Cadastros\Usuario\User;

class CustomResetPassword extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Obtenha o e-mail do notifiable (usuário)
        $email = $notifiable->email;

        // Busque o usuário no banco de dados
        $user = User::where('email', $email)->first();

        // Agora você pode usar o $user para personalizar o e-mail
        // Exemplo: pegar o nome do usuário
        $name = $user ? $user->name : 'Usuário';

        return (new MailMessage)
            ->subject('Redefinição de Senha Solicitada')
            ->greeting('Olá, '.$name)
            ->line('Recebemos uma solicitação de redefinição de senha para sua conta.')
            ->action('Redefinir Senha', url(route('password.reset', $this->token, false)))
            ->line('Se você não solicitou essa alteração, nenhuma ação adicional é necessária.')
            ->salutation('Atenciosamente, Nexus ERP Cloud');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
