<?php

namespace App\Listeners;

use App\Events\BotCreating;
use Illuminate\Support\Facades\Auth;

class AssignBotToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BotCreating $event
     * @return void
     */
    public function handle(BotCreating $event)
    {
        $event->bot->creator_id = Auth::user()->id;
    }
}