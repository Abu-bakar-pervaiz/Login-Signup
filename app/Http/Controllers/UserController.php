<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseCollection;
use App\Http\Resources\ValidationCollection;
use App\Http\Resources\ErrorCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // * LOGIN API
    public function login(Request $request)
    {

        // * CHECK FOR VALIDATION
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // * IF ERROR EXIST RETURN ERROR
        if ($validator->fails()) {
            return (new ValidationCollection($validator->errors()->all()))
                ->response()
                ->setStatusCode(400);
        }

        // * CHECK IF USER EXIST
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return (new ErrorCollection(['Invalid Password']))
                ->response()
                ->setStatusCode(403);
        }

        // * RETURN TOKEN AND USER DATA
        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => $user,
            'accessToken' => $token,
        ];
        return (new ResponseCollection($response))
            ->response()
            ->setStatusCode(200);
    }

    // * LOGOUT API
    public function logout()
    {

        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];
    }


    // * REGISTER API
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
            'role' => 'required',
        ]);

        // * IF ERROR EXIST RETURN ERROR
        if ($validator->fails()) {
            return (new ValidationCollection($validator->errors()->all()))
                ->response()
                ->setStatusCode(400);
        }

        // * CREATE USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'role' => $request->role,
        ]);

        // * RETURN TOKEN AND USER DATA
        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'accessToken' => $token,
        ];

        return (new ResponseCollection($response))
            ->response()
            ->setStatusCode(200);
    }
}
