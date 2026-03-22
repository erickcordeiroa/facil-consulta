<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentService
{
    /**
     * @param  array{doctor_id: int|string, date: string, hour: string}  $validated
     */
    public function createForPatient(Patient $patient, array $validated): Appointment
    {
        $appointment = Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => $patient->id,
            'date' => $validated['date'],
            'hour' => $validated['hour'],
        ]);

        $date = Carbon::parse($validated['date']);
        $weekday = $date->dayOfWeek;
        $doctorSchedule = DoctorSchedule::where('doctor_id', $validated['doctor_id'])
            ->where('weekday', $weekday)
            ->where('time', $validated['hour'])
            ->first();
        if ($doctorSchedule) {
            $doctorSchedule->is_available = false;
            $doctorSchedule->save();
            $appointment->doctor_schedule_id = $doctorSchedule->id;
            $appointment->save();
        }

        return $appointment;
    }

    /**
     * @return array{upcoming: Collection<int, Appointment>, previous: Collection<int, Appointment>}
     */
    public function listGroupedForPatient(Patient $user): array
    {
        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i');

        $appointments = Appointment::with(['doctorSchedule.doctor'])
            ->where('patient_id', $user->id)
            ->join('doctor_schedules', 'appointments.doctor_schedule_id', '=', 'doctor_schedules.id')
            ->join('doctors', 'doctor_schedules.doctor_id', '=', 'doctors.id')
            ->orderBy('doctors.name', 'desc')
            ->orderBy('date', 'asc')
            ->orderBy('hour', 'asc')
            ->get();

        $upcoming = $appointments->filter(function ($a) use ($today, $currentTime) {
            if ($a->date > $today) {
                return true;
            }
            if ($a->date < $today) {
                return false;
            }

            return $a->hour > $currentTime;
        })->values();
        $previous = $appointments->filter(function ($a) use ($today, $currentTime) {
            if ($a->date < $today) {
                return true;
            }
            if ($a->date > $today) {
                return false;
            }

            return $a->hour <= $currentTime;
        })->values();

        return [
            'upcoming' => $upcoming,
            'previous' => $previous,
        ];
    }
}
