<?php

namespace App\Notifications;

use App\Models\Tarefa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReadyForQaNotification extends Notification
{
    use Queueable;

    public function __construct(public Tarefa $tarefa)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->line('Uma tarefa foi movida para QA.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tarefa_id' => $this->tarefa->id,
            'titulo' => $this->tarefa->titulo,
            'mensagem' => 'A tarefa está pronta para testes de QA.',
        ];
    }
}
