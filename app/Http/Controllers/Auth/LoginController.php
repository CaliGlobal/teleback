<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ApiResponses;

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->unauthorizedResponse('The provided credentials are incorrect');
        }
        $token = $user->createToken($request->validated('email'))->plainTextToken;
        return $this->successResponse(['message' => 'Logged In Successfully','token' => $token, 'user' => $user]);
    }
}
