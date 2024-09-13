<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Rental;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class MarkOverdueRentalsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test marking overdue rentals.
     */
    public function test_mark_overdue_rentals_command_marks_rentals_as_overdue()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $rental = Rental::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'return_date' => Carbon::now()->subDays(15), // Overdue by 15 days
            'status' => 'rented',
        ]);

        // Run the command to mark overdue rentals
        Artisan::call('rentals:mark-overdue');

        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'overdue',
        ]);
    }
}