<?php

use Illuminate\Support\Facades\Broadcast;
use Barryvdh\Debugbar\Facades\Debugbar;

Broadcast::channel('chat.{id}', function ($user, $receiverId) {
    Debugbar::info($user);
    Debugbar::info($receiverId);
    return (int) $user->id === (int) $receiverId;
});
