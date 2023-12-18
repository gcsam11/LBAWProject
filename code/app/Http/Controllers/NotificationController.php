<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function unreadNotifications()
    {
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications;
        \Log::info('Notifications: ' . $unreadNotifications);
        return response()->json($unreadNotifications, 200);
    }
    //Need to markAsRead() somewhere $user->unreadNotifications->markAsRead();
}