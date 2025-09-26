<?php

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * This method is used to login admin or staff
     * 
     * @param Request $request
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Credentials do not match', 401);
        }

        $user->token = $user->createToken("auth_token_{$user->id}")->plainTextToken;

        return $this->successResponse(UserResource::make($user->load('permissions')), 'User logged in successfully');
    }
}
