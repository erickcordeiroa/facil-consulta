<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorListingResource;
use App\Services\DoctorAvailabilityService;

class DoctorController extends Controller
{
    public function __construct(
        private DoctorAvailabilityService $doctorAvailabilityService
    ) {}

    public function index()
    {
        $result = $this->doctorAvailabilityService->listDoctorsWithNextFiveDays();

        return response()->json(
            DoctorListingResource::collection(collect($result))->resolve()
        );
    }
}
