<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['laki-laki', 'perempuan']);
        $name = $gender === 'laki-laki' ? fake('id_ID')->name('male') : fake('id_ID')->name('female');
        return [
            'name' => $name,
            'gender' => $gender,
            'date_of_birth' => fake()->dateTimeBetween('2007-01-01', '2008-12-30'),
            'phone_number' => fake()->phoneNumber()
        ];
    }
}
