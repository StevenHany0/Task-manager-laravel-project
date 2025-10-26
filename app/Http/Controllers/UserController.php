<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
   public function register(Request $request){
    $request->validate([
        'name'=>'required|string|max:255',
        'email'=>'required|string|email|max:255|unique:users,email',
        'password'=>'required|string|min:8',
    ]);
    $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password)
    ]);
    return response()->json(['message'=>'User registered successfully','user'=>$user], 201);

   }

   public function login(Request $request){
    $request->validate([
        'email'=>'required|string|email',
        'password'=>'required|string'
    ]);
    if(!Auth::attempt($request->only('email','password'))){
        return response()->json(['message'=>'Invalid credentials'], 401);
    }
    $user = Auth::user()->where('email',$request->email)->firstOrFail();
    $token=$user->createToken('auth_token')->plainTextToken;
    return response()->json(['message'=>'User logged in successfully','user'=>$user,'access_token'=>$token], 200);

   }
   
   public function logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message'=>'User logged out successfully'], 200);
   }

    public function getprofile($id){
        $profile =User::findOrFail($id)->profile;
        return response()->json($profile,200);

    }

    public function gettasks($id){
        $tasks =User::findOrFail($id)->tasks;
        return response()->json($tasks,200);

    }
}
