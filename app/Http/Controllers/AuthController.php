<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public $loginAfterSignUp = true;

    public function login(Request $request)
    {

        $credentials = $request->only("email", "password");
        $token = null;

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                "status" => false,
                "message" => "Unauthorized",
            ]);
        }

        return response()->json([
            "status" => true,
            "token" => $token,
        ]);

    } //end of the login method

    public function register(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|string|min:8|max:10",
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        if ($this->loginAfterSignUp) {

            return $this->login($request);
        }

        return response()->json([
            "status" => true,
            "user" => $user,
        ]);

    } //end of the register method

    public function logout(Request $request)
    {
        $this->validate($request, [
            "token" => "required",
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                "status" => true,
                "message" => "User logged out Succssfully",
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                "status" => false,
                "message" => "Ops, The user can not be logged out",
            ]);
        }

    } //end of the logout method

} //end of the AuthController class