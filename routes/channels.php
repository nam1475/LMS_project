<?php

use Illuminate\Support\Facades\Broadcast;
use Barryvdh\Debugbar\Facades\Debugbar;

Broadcast::channel('chat.{id}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId;
});

// Broadcast::channel('notificationssss.{id}', function ($user, $receiverId) {
//     return (int) $user->id === (int) $receiverId;
// });
