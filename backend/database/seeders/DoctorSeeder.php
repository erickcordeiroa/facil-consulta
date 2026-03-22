<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::insert([
            [
                'name' => 'Dr. Fernando Behrensdorf',
                'specialty' => 'Cardiologista',
                'address' => 'Av. Regente Feijó, 450, sala 602, Centro, Registro - SP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Marcos Eduardo Avancini Schenatto',
                'specialty' => 'Cardiologista',
                'address' => 'Rua XV de Novembro, 755, sala 104, Centro, Registro - SP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Carlos Osorio Magalhães',
                'specialty' => 'Cardiologista',
                'address' => 'Rua Coronel João Dias Guimarães, 280, sala 206, Registro - SP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. João Paulo de Souza',
                'specialty' => 'Cardiologista',
                'address' => 'Av. Brasil, 123, sala 301, Vila Nossa Senhora Aparecida, Registro - SP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Ana Clara Ribeiro',
                'specialty' => 'Cardiologista',
                'address' => 'Rua Capitão José Leite, 456, sala 202, Centro, Registro - SP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Cris',
                'specialty' => 'Clínico Geral',
                'address' => 'Rua João Pessoa, 210, Jardim São Paulo, Registro - SP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
