<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Invitation;
use Notification;
use App\Notifications\InviteRegistrationOTPNotification;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8',
            'user_name' => 'required|min:4|max:20',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $invite = Invitation::where('email', $request->email)->first();
        if(!empty($invite)){
            $invite->delete();
            $six_digit_random_number = random_int(100000, 999999);
            Notification::route('mail', $request->input('email'))->notify(new InviteRegistrationOTPNotification($six_digit_random_number));
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_name' => $request->user_name,
                'user_role' => "USER",
                'registered_at' => date("Y-m-d H:i:s"),
                'user_status' => "PENDING",
                'otp' => $six_digit_random_number,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()
            ->json(['resposeCode' => 200,'message' => 'Please check your email to verify OTP for complete registration' ]);
        }else{
            return response()->json(array('resposeCode'=>404,'message'=>"Record Not Found!"));
        }
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'otp' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::where('otp', $request->otp)->first();
        if(!empty($user)){
            $six_digit_random_number = random_int(100000, 999999);
            $userUpdate = User::where('id', $user->id)->update([
                'email_verified_at' => date("Y-m-d H:i:s"),
                'user_status' => "APPROVED",
                'otp' => NULL,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()
            ->json(['resposeCode' => 200,'message' => 'Congratulations, your account has been successfully created!' ]);
        }else{
            return response()->json(array('resposeCode'=>404,'message'=>"Record Not Found!"));
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))){
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->where('user_status', "APPROVED")->first();

        if(!empty($user)){
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
        }else{
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }

    public function updateUserProfile(Request $request){ 

        $userprofile = Auth::user(); // Used this very authenticated user object to update the new values below

        $validator = Validator::make($request->all(),[
            'user_name' => 'required|string|min:4|max:20|unique:users,user_name,'.$userprofile->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$userprofile->id,
            'password' => 'min:8|confirmed',
            'avatar' => 'file|image|max:256'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        // File upload
        if (request()->hasFile('avatar')){
            $path1 = request()->file('avatar')->store('Userprofile', 'public');
            $path1 = url('storage/'.$path1);
        }else{
            $path1 = $userprofile->avatar;
        }
        // This "$userprofile" variable is the authenticated user object I mentioned at the top which I am using here to update new values
        
        $userprofile->update([
            'user_name' => $request->user_name,
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $path1,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['message' => 'Your profile successfully updated.'], 200);
    }
}
