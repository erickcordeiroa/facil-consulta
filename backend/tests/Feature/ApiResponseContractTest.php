<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiResponseContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctors_index_returns_unwrapped_array_at_root(): void
    {
        Doctor::create([
            'name' => 'Dr Test',
            'specialty' => 'Cardiologia',
            'address' => 'Rua 1',
        ]);

        $response = $this->getJson('/api/doctors');

        $response->assertOk();
        $json = $response->json();
        $this->assertIsArray($json);
        $this->assertArrayNotHasKey('data', $json);
        $this->assertNotEmpty($json);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'specialty',
                'address',
                'days' => [
                    '*' => ['date', 'weekday', 'weekday_name', 'hours'],
                ],
            ],
        ]);
    }

    public function test_register_returns_auth_envelope_without_data_wrapper(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'patient' => ['id', 'name', 'email'],
            'message',
        ]);
        $response->assertJsonPath('token_type', 'Bearer');
        $response->assertJsonPath('message', 'Registrado com sucesso.');
        $this->assertArrayNotHasKey('password', $response->json('patient'));
        $this->assertArrayNotHasKey('data', $response->json());
    }

    public function test_me_returns_patient_object_at_root(): void
    {
        $patient = Patient::create([
            'name' => 'Patient',
            'email' => 'patient@example.com',
            'password' => Hash::make('password'),
        ]);

        Sanctum::actingAs($patient);

        $response = $this->getJson('/api/auth/me');

        $response->assertOk();
        $json = $response->json();
        $this->assertArrayHasKey('id', $json);
        $this->assertArrayHasKey('email', $json);
        $this->assertArrayNotHasKey('data', $json);
        $this->assertArrayNotHasKey('password', $json);
    }

    public function test_store_appointment_returns_201_with_message_and_appointment(): void
    {
        $patient = Patient::create([
            'name' => 'Patient',
            'email' => 'p2@example.com',
            'password' => Hash::make('password'),
        ]);
        $doctor = Doctor::create([
            'name' => 'Dr B',
            'specialty' => 'Clínico',
            'address' => 'Rua 2',
        ]);

        Sanctum::actingAs($patient);

        $response = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->toDateString(),
            'hour' => '09:00',
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'message',
            'appointment' => ['id', 'patient_id', 'doctor_id', 'date', 'hour'],
        ]);
        $response->assertJsonPath('message', 'Consulta agendada com sucesso!');
    }

    public function test_appointments_index_returns_upcoming_and_previous_arrays(): void
    {
        $patient = Patient::create([
            'name' => 'Patient',
            'email' => 'p3@example.com',
            'password' => Hash::make('password'),
        ]);
        Sanctum::actingAs($patient);

        $response = $this->getJson('/api/appointments');

        $response->assertOk();
        $response->assertJsonStructure([
            'upcoming',
            'previous',
        ]);
        $this->assertArrayNotHasKey('data', $response->json());
    }
}
