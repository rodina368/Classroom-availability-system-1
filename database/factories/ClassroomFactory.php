<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classroom>
 */
class ClassroomFactory extends Factory
{
    protected static int $counter = 0;

    protected static array $classrooms = [
        ['name' => 'Classroom A101', 'location' => 'Engineering Wing', 'capacity' => 40],
        ['name' => 'Classroom A102', 'location' => 'Engineering Wing', 'capacity' => 30],
        ['name' => 'Classroom B201', 'location' => 'Business Wing', 'capacity' => 60],
        ['name' => 'Classroom B202', 'location' => 'Business Wing', 'capacity' => 50],
        ['name' => 'Classroom C301', 'location' => 'IT Wing', 'capacity' => 35],
        ['name' => 'Classroom C302', 'location' => 'IT Wing', 'capacity' => 25],
        ['name' => 'Classroom D401', 'location' => 'Medical Wing', 'capacity' => 80],
        ['name' => 'Classroom D402', 'location' => 'Medical Wing', 'capacity' => 60],
        ['name' => 'Classroom E101', 'location' => 'General Studies Wing', 'capacity' => 45],
        ['name' => 'Classroom E102', 'location' => 'General Studies Wing', 'capacity' => 55],
        ['name' => 'Classroom F201', 'location' => 'Science Wing', 'capacity' => 30],
        ['name' => 'Classroom F202', 'location' => 'Science Wing', 'capacity' => 70],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = static::$counter % count(static::$classrooms);
        $data = static::$classrooms[$index];
        static::$counter++;

        return [
            'name' => $data['name'],
            'location' => $data['location'],
            'capacity' => $data['capacity'],
            'equipment' => $this->faker->randomElements(['projector', 'whiteboard', 'computers', 'audio_system', 'smart_board'], $this->faker->numberBetween(1, 3)),
            'is_active' => true,
        ];
    }
}
