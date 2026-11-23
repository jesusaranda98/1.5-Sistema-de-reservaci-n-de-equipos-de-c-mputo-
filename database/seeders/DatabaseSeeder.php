<?php

namespace Database\Seeders;

use App\Models\ComputerEquipment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@laboratorio.local'],
            [
                'name' => 'Admin Laboratorio',
                'password' => Hash::make('Admin1234!'),
                'role' => 'administrador',
            ]
        );

        User::updateOrCreate(
            ['email' => 'estudiante1@laboratorio.local'],
            [
                'name' => 'Sofía Ramírez',
                'password' => Hash::make('Estudiante1234!'),
                'role' => 'estudiante',
            ]
        );

        User::updateOrCreate(
            ['email' => 'estudiante2@laboratorio.local'],
            [
                'name' => 'Carlos Mendoza',
                'password' => Hash::make('Estudiante1234!'),
                'role' => 'estudiante',
            ]
        );

        $equipmentList = [
            ['PC-LAB-01', 'Estación Workstation 01', 'Ryzen 7, 32GB RAM, RTX 4060', 'Lab Cómputo 1', 'Disponible'],
            ['PC-LAB-02', 'Estación Workstation 02', 'Ryzen 7, 32GB RAM, RTX 4060', 'Lab Cómputo 1', 'Disponible'],
            ['PC-LAB-03', 'Estación Desarrollo 03', 'Core i7, 16GB RAM, SSD 1TB', 'Lab Cómputo 1', 'Disponible'],
            ['PC-LAB-04', 'Estación Desarrollo 04', 'Core i7, 16GB RAM, SSD 1TB', 'Lab Cómputo 1', 'Disponible'],
            ['LAP-LAB-05', 'Laptop Ingeniería 05', 'Core i5, 16GB RAM, Pantalla 15.6"', 'Lab Cómputo 2', 'Disponible'],
            ['LAP-LAB-06', 'Laptop Ingeniería 06', 'Core i5, 16GB RAM, Pantalla 15.6"', 'Lab Cómputo 2', 'Disponible'],
            ['PC-LAB-07', 'Estación Render 07', 'Ryzen 9, 64GB RAM, RTX 4070', 'Lab Cómputo 2', 'Mantenimiento'],
            ['PC-LAB-08', 'Estación Simulación 08', 'Core i7, 32GB RAM, SSD 1TB', 'Lab Cómputo 2', 'Disponible'],
        ];

        foreach ($equipmentList as [$code, $name, $specs, $lab, $status]) {
            ComputerEquipment::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'specifications' => $specs,
                    'lab_location' => $lab,
                    'status' => $status,
                ]
            );
        }
    }
}