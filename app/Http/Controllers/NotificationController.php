<?php

namespace App\Http\Controllers;

use App\Events\NotificationButtonClicked;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index()
    {
        return view('email');
    }

    public function notification(Request $request)
    {
        $email = $request->get('email');
        event(new NotificationButtonClicked($email));

        return back();
    }

}
