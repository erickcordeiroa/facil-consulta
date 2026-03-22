<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     * @return array{access_token: string, token_type: string, patient: Patient, message: string}
     */
    public function register(array $data): array
    {
        $patient = Patient::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $patient->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'patient' => $patient,
            'message' => 'Registrado com sucesso.',
        ];
    }

    /**
     * @param  array{email: string, password: string}  $data
     * @return array{access_token: string, token_type: string, patient: Patient, message: string}
     */
    public function login(array $data): array
    {
        $patient = Patient::where('email', $data['email'])->first();

        if (! $patient || ! Hash::check($data['password'], $patient->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email ou senha estão incorretos.'],
            ]);
        }

        $token = $patient->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'patient' => $patient,
            'message' => 'Logado com sucesso.',
        ];
    }
}
