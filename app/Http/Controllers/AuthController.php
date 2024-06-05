<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{


    public function __construct( private AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function signup(SignupRequest $request): JsonResponse
    {
        $user = $this->authService->signup($request->validated());
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $tokenData = $this->authService->login($request->validated());
        return response()->json($tokenData, 200);
    }
}