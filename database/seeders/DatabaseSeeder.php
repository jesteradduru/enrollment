<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Level;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin', 
        ]);

        SchoolYear::create([
            'start_year' => 2023,
            'end_year' => 2024,
        ]);

        Level::create([
            'level' => 'Grade 1',
        ]);

        Classroom::create([
            'name' => 'Class A',
            'level_id' => 1, // Assuming Level ID 1 exists
        ]);

        Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'M',
            'extension_name' => 'Jr.',
            'date_of_birth' => '2010-01-01',
            'gender' => 'male',
            'address' => '123 Main St',
            'type' => 'new',
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'faculty1@mail.com',
            'role' => 'faculty',
        ]);


    }
}
