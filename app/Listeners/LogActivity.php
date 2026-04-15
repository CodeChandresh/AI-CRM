<?php

namespace App\Listeners;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogActivity implements ShouldQueue
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = Auth::user();
        $model = $event->model;
        $action = $event->action;

        $activity = new Activity();
        $activity->user_id = $user->id;
        $activity->model_type = get_class($model);
        $activity->model_id = $model->id;
        $activity->action = $action;
        $activity->description = $model->description;
        $activity->save();

        Log::info("Activity logged: User {$user->name} performed {$action} on {$model->name}");
    }
}