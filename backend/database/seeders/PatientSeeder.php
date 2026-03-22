<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'name' => 'Erick Cordeiro',
            'email' => 'erickcordeiroa@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
