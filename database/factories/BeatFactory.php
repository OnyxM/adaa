<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beat>
 */
class BeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->jobTitle;

        return [
            'user_id' => rand(1, User::count()),
            'title' => $title,
            'slug' => Str::slug($title)."-".time(),
            'premium_file' => $this->faker->url,
            'free_file' => $this->faker->url,
        ];
    }
}
