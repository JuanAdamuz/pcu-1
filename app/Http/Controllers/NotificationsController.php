<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();

        // Mark unread notifications as read
        $user->unreadNotifications->markAsRead();

        // Return to previous page
        return redirect()->back()->with('status', 'Notificaciones marcadas como leídas');
    }
}
