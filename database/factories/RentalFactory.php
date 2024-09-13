<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class RentalFactory extends Factory
{
    protected $model = \App\Models\Rental::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'book_id' => \App\Models\Book::factory(),
            'rented_at' => Carbon::now(),
            'return_date' => Carbon::now()->addWeeks(2),
            'status' => 'rented',
        ];
    }
}