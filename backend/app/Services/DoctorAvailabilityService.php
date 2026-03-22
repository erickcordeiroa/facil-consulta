<?php

namespace App\Services;

use App\Models\Doctor;
use Carbon\Carbon;

class DoctorAvailabilityService
{
    /**
     * @return list<array{id: int, name: string, specialty: string, address: string, days: list<array{date: string, weekday: int, weekday_name: string, hours: list<string>}>}>
     */
    public function listDoctorsWithNextFiveDays(): array
    {
        $doctors = Doctor::with(['schedules' => function ($query) {
            $query->where('is_available', true)
                ->orderBy('weekday')
                ->orderBy('time');
        }])->orderBy('name', 'desc')->get();

        $result = [];

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        foreach ($doctors as $doctor) {
            $days = [];
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::today()->addDays($i);
                $weekday = $date->dayOfWeek;

                if ($date->isSameDay($today)) {
                    $displayWeekdayName = 'Hoje';
                } elseif ($date->isSameDay($tomorrow)) {
                    $displayWeekdayName = 'Amanhã';
                } else {
                    $displayWeekdayName = $this->getWeekdayName($weekday);
                }

                $hoursQuery = $doctor->schedules->where('weekday', $weekday)->pluck('time');
                if ($date->isSameDay($today)) {
                    $currentTime = Carbon::now()->format('H:i:s');
                    $hours = $hoursQuery->filter(function ($time) use ($currentTime) {
                        return $time > $currentTime;
                    })->values()->all();
                } else {
                    $hours = $hoursQuery->values()->all();
                }

                $days[] = [
                    'date' => $date->toDateString(),
                    'weekday' => $weekday,
                    'weekday_name' => $displayWeekdayName,
                    'hours' => $hours,
                ];
            }

            $result[] = [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'specialty' => $doctor->specialty,
                'address' => $doctor->address,
                'days' => $days,
            ];
        }

        return $result;
    }

    private function getWeekdayName(int $weekday): string
    {
        $days = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

        return $days[$weekday];
    }
}
