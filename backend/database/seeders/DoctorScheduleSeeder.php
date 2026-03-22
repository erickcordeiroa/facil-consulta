<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $allTimes = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        $weekdays = [0, 1, 2, 3, 4, 5, 6];

        foreach ($doctors as $doctor) {
            foreach ($weekdays as $weekday) {
                $randomTimes = collect($allTimes)->shuffle()->take(3);

                foreach ($randomTimes as $time) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'weekday' => $weekday,
                        'time' => $time,
                        'is_available' => true,
                    ]);
                }
            }
        }
    }
}
