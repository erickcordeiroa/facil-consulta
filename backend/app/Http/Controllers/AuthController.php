<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginPatientRequest;
use App\Http\Requests\RegisterPatientRequest;
use App\Http\Resources\AuthTokenResource;
use App\Http\Resources\PatientResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function register(RegisterPatientRequest $request)
    {
        return new AuthTokenResource($this->authService->register($request->validated()));
    }

    public function login(LoginPatientRequest $request)
    {
        return new AuthTokenResource($this->authService->login($request->validated()));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Desconectado com sucesso.']);
    }

    public function me(Request $request)
    {
        return new PatientResource($request->user());
    }
}
