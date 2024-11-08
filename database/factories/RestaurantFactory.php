<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->email,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude
        ];
        }
    }
