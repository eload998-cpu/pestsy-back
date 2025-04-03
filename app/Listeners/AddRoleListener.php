<?php

namespace App\Listeners;

use App\Events\AddRoleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\{Role,User};

class AddRoleListener
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
     * @param  \App\Events\AddRoleEvent  $event
     * @return void
     */
    public function handle(AddRoleEvent $event)
    {
        $user=User::find($event->user_id);
        $role=Role::where('name',$event->role_name)->first();

        $user->roles()->detach();
        $user->roles()->attach($role->id);
        
    }
}
