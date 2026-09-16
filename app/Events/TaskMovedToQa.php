<?php

namespace App\Events;

use App\Models\Tarefa;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskMovedToQa
{
    use Dispatchable, SerializesModels;

    public function __construct(public Tarefa $tarefa)
    {
    }
}
