<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "password" => "required",
            "confirm_password" => "required|same:password"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 1,
                "message" => $validator->errors()->all()
            ]);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        $token = $user->createToken("my_app")->plainTextToken;

        return response()->json([
            "status" => 1,
            "message" => "User Registered!",
            "token" => $token
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();

            return response()->json([
                "status" => 1,
                "message" => "User Login!",
                "user" => $user->only("email", "name"),
                "token" => $user->createtoken("my_app")->plainTextToken
            ]);
        }

        return response()->json([
            "status" => 0,
            "message" => "Authentication Error!"
        ]);
    }
}
