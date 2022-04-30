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
    public function login( Request $request ){

        $validator = Validator::make($request->all(),[
            'email'=>'required|string|email',
            'password'=>'required|string',
        ]);    
        
        if ($validator->fails()) {

            return ( new ValidationCollection($validator->errors()->all()) )
            ->response()
            ->setStatusCode(400);

        }

        $user = User::where('email',$request->email)->first();
        if (!$user || !Hash::check($request->password,$user->password)) {
            return ( new ErrorCollection(['Invalid Password']) )
            ->response()
            ->setStatusCode(403);
        }
        
        $token = $user->createToken('myAppToken')->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token,
        ];
        return ( new ResponseCollection($response) )
            ->response()
            ->setStatusCode(200);
        
    }

    public function logout( Request $request ){

        auth()->user()->tokens()->delete();

        return [
            'message'=>'Logged Out'
        ];
        
    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users,email',
            'password'=>'required|string|confirmed',
            'role'=>'required',
        ]);    
        
        if ($validator->fails()) {

            return ( new ValidationCollection($validator->errors()->all()) )
            ->response()
            ->setStatusCode(400);

        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'name'=>$request->name,
            'role'=>$request->role,
        ]);
        
        $token = $user->createToken('myAppToken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token,
        ];

        return ( new ResponseCollection( $response ) )
        ->response()
        ->setStatusCode( 200 );
        
    }
}
