<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $data = $request->validated();
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    =>$request->phone,
            'password' => bcrypt($request->password),
        ]);
        $user->sendEmailVerificationNotification();
        return response()->json([
            'success'=>true,
            'message'=>'Account created successfully. Please verify your email.',
            'data'=>$user
        ]);
    }
    public function verifyEmail($id,$hash){
        
        $user = User::find($id)->first();   
        
        if(!$user){
            return response()->json([
                'message'=>'User is not found'
            ],404);
        }

        if(!hash_equals(sha1($user->getEmailForVerification()),$hash)){
            return response()->json([
                'message'=>'Invalid verification link..!'
            ],403);
        }

        if($user->hasVerifiedEmail()){
            return response()->json([
                'message'=>'Email already verified'
            ]);
        }
        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully'
        ]);
    }
    public function login(LoginRequest $request){
        $data = $request->validated();

        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password ,$user->password)){
            return response()->json([
                'message'=>'Your Email and password invalid '
            ],401);
        }

        if(!$user->hasVerifiedEmail()){
            return response()->json([
                'message'=>'please verify your email first'
            ],403);
        }
        // dd($user);
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => $user
        ]);
        
    }
    public function logout(Request $request){
        $user = $request->user();
        if(!$user){
            return response()->json([
                'message'=>'user not authenticated'
            ]);
        }
        $user->tokens()->delete();
        return response()->json([
            'message'=>'logout successfully'
        ]);
    }
}
