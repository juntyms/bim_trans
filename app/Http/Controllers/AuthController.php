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

    /**
     * @OA\Post(
     *  tags={"User"},
     *  path="/api/v1/register",
     *  summary="Register user",
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Fullname",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="email",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          required=true,
     *      ),
     *  @OA\Response(response=200,description="OK"),
     *  @OA\Response(response=401,description="Unauthorized")
     * )
     *
     *
     * @param StoreUserRequest $request
     * @return void
     */
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

    /**
     * @OA\Post(
     *  tags={"User"},
     *  path="/api/v1/login",
     *  summary="logging in user",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="email",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *      ),
     *  @OA\Response(response=200,description="OK"),
     *  @OA\Response(response=401,description="Unauthorized")
     * )
     */
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

    /**
     * @OA\POST(
     *  tags={"User"},
     *  path="/api/v1/logout",
     *  summary="Logging out user",
     *  security={ {"bearerToken": {}} },
     *  @OA\Response(response=200, description="OK"),
     *  )
     *
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message'=> 'You have been logged out and your token has been deleted'
        ]);
    }

}
