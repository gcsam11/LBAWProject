<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PasswordRecoveryController;
use Mail;
use App\Mail\MailModel;

class MailController extends Controller
{
    function send(Request $request) {
        // Call the create method of PasswordRecoveryController
        PasswordRecoveryController::create($request);

        $mailData = [
            'email' => $request->email,
            'token' => $request->token,
        ];

        Mail::to($request->email)->send(new MailModel($mailData));
        return redirect()->route('login');
    }

    
    function showRecoverPassForm()
    {
        return view('pages.recover_password');
    }
}