<?php

namespace App\Listeners;

use App\Events\TaskMovedToQa;
use App\Notifications\TaskReadyForQaNotification;

class NotifyQaAssignee
{
    public function handle(TaskMovedToQa $event): void
    {
        $event->tarefa->loadMissing('user');
        $event->tarefa->user?->notify(new TaskReadyForQaNotification($event->tarefa));
    }
}
