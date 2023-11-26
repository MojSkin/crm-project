<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('private-user-alarms-{username}', function ($user, $username) {
    dd('hi');
    return true;

    dd($user, $username);
    return strtoupper($user->username) === strtoupper($id);
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    dd('hi');
    return (int) $user->id === (int) $id;
});
