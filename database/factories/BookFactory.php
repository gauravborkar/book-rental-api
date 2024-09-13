<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'genre' => $this->faker->word,
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}