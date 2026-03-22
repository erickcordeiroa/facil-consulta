<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\PatientAppointmentsResource;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService
    ) {}

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->createForPatient(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Consulta agendada com sucesso!',
            'appointment' => (new AppointmentResource($appointment))->resolve(),
        ], 201);
    }

    public function index(Request $request)
    {
        $grouped = $this->appointmentService->listGroupedForPatient($request->user());

        return new PatientAppointmentsResource($grouped);
    }
}
