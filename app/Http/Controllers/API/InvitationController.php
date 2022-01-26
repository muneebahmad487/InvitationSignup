<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use Validator;
use URL;
use Illuminate\Support\Str;
use Notification;
use App\Notifications\InviteNotification;

class InvitationController extends Controller
{
    //
    public function sendInvites(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);
        $validator->after(function ($validator) use ($request) {
        if (Invitation::where('email', $request->input('email'))->exists()) {
            $validator->errors()->add('email', 'There exists an invite with this email!');
        }
        });
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        do {
            $token = Str::random(20);
        } while (Invitation::where('invitation_token', $token)->first());
        Invitation::create([
            'invitation_token' => $token,
            'email' => $request->input('email')
        ]);
        $url = URL::temporarySignedRoute(
            'registration', now()->addMinutes(300), ['token' => $token]
        );
        Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));
        return response()->json(array('resposeCode'=>200,'comment'=>"The Invite has been sent successfully"));
    }
    public function accept($token){
        // here we'll look up the user by the token sent provided in the URL

        
    }

    public function registration_view($token)
    {
        $invite = Invitation::select('email')->where('invitation_token', $token)->first();
        return response()->json(array('resposeCode'=>200,'invitation_detail'=>$invite));
    }
}
