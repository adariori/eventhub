<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'lieu' => $this->faker->city(),
            'cover_path' => 'covers/'.$this->faker->uuid(),
            'user_id' => User::factory(),
        ];
    }
}
