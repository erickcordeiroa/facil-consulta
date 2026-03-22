<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentListItemResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Appointment $appointment */
        $appointment = $this->resource;

        return [
            'id' => $appointment->id,
            'date' => $appointment->date,
            'hour' => $appointment->hour,
            'doctor' => [
                'name' => $appointment->doctorSchedule && $appointment->doctorSchedule->doctor ? $appointment->doctorSchedule->doctor->name : null,
                'specialty' => $appointment->doctorSchedule && $appointment->doctorSchedule->doctor ? $appointment->doctorSchedule->doctor->specialty : null,
                'address' => $appointment->doctorSchedule && $appointment->doctorSchedule->doctor ? $appointment->doctorSchedule->doctor->address : null,
            ],
        ];
    }
}
