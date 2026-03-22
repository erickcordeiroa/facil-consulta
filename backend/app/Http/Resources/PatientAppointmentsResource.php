<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PatientAppointmentsResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Collection $upcoming */
        $upcoming = $this->resource['upcoming'];
        /** @var Collection $previous */
        $previous = $this->resource['previous'];

        return [
            'upcoming' => AppointmentListItemResource::collection($upcoming)->resolve(),
            'previous' => AppointmentListItemResource::collection($previous)->resolve(),
        ];
    }
}
