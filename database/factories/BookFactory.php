<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'genre' => $this->faker->word,
            'author' => $this->faker->name,
            'isbn' => $this->faker->numberBetween(1, 10),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}