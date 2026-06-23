<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'university_id' => 'ADMIN001',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'administrator',
            'department' => 'Administration',
        ]);

        // Demo user matching the screenshot
        $ahmed = User::factory()->create([
            'name' => 'Ahmed Al-Mansouri',
            'university_id' => 'STU2024001',
            'email' => 'ahmed@limu.edu.ly',
            'password' => 'password',
            'role' => 'lecturer',
            'department' => 'Engineering',
        ]);

        // Create 12 LIMU classrooms
        $classrooms = Classroom::factory(12)->create();

        // Create some sample reservations to show Occupied status
        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        // Make a few rooms occupied for today
        Reservation::create([
            'classroom_id' => $classrooms[1]->id, // A102
            'user_id' => $ahmed->id,
            'start_time' => Carbon::parse($today . ' 08:00'),
            'end_time' => Carbon::parse($today . ' 12:00'),
            'purpose' => 'Engineering Fundamentals Lecture',
            'status' => 'approved',
        ]);

        Reservation::create([
            'classroom_id' => $classrooms[3]->id, // B202
            'user_id' => $admin->id,
            'start_time' => Carbon::parse($today . ' 09:00'),
            'end_time' => Carbon::parse($today . ' 11:00'),
            'purpose' => 'Faculty Meeting',
            'status' => 'approved',
        ]);

        Reservation::create([
            'classroom_id' => $classrooms[5]->id, // C302
            'user_id' => $ahmed->id,
            'start_time' => Carbon::parse($today . ' 10:00'),
            'end_time' => Carbon::parse($today . ' 14:00'),
            'purpose' => 'Database Systems Lab',
            'status' => 'approved',
        ]);

        Reservation::create([
            'classroom_id' => $classrooms[8]->id, // E101
            'user_id' => $admin->id,
            'start_time' => Carbon::parse($today . ' 13:00'),
            'end_time' => Carbon::parse($today . ' 17:00'),
            'purpose' => 'General Studies Exam',
            'status' => 'approved',
        ]);
    }
}
