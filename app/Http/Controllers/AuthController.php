<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of '. $user->name)->plainTextToken
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email','password'))) {

            return $this->error('','Credentials do not match',401);

        }

        $request->session()->regenerate(); // Session for web use

        return $this->success([
            'user' => Auth::user(),
            'token' => Auth::user()->createToken('Api Token of '. Auth::user()->name)->plainTextToken
        ]);
    }


    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message'=> 'You have been logged out and your token has been deleted'
        ]);
    }

}
