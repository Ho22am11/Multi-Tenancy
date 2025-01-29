<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthController extends Controller
{
    use ApiResponseTrait ;

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->ApiResponse($validator->errors(), 'register not successed', 400);
        }

        $user = User::create([
            'name' => $request->name ,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = auth()->attempt($request->only('email' , 'password'));

        $user->token = $token ;

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->ApiResponse($user , 'register successfuly' , 201 );
    }


    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->ApiResponse($validator->errors(), 'register not successed', 400);
            }

            $credentials = ['email' => $request->email , 'password' => $request->password];
            $token = auth()->attempt($credentials);

            if (!$token) {
                return$this->ApiResponse( null , 'Unauthorized. Invalid credentials', 401);
            }

            $user = auth()->user();
            $user->token = $token;

            return $this->ApiResponse($user , 'register successfuly' , 201 );



        }catch(Exception $e){
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request){
        $token = $request->bearerToken();

        JWTAuth::setToken($token)->invalidate();

        return $this->ApiResponse( null , 'Logged out successfully' , 201);
    }

    public function refresh(Request $request){
        $token = $request->bearerToken();
        
        JWTAuth::setToken($token)->refresh();

        return $this->ApiResponse( $token , 'refresh successfully' , 201);
    
    
    }
}
