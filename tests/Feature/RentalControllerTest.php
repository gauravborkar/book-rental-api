<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Rental;
use Carbon\Carbon;

class RentalControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test renting a book.
     */
    public function test_user_can_rent_a_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['quantity' => 5]);

        $response = $this->actingAs($user, 'api')->postJson('/api/v1/books/rent', [
            'book_id' => $book->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('rentals', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'rented',
        ]);
    }

    /**
     * Test returning a book.
     */
    public function test_user_can_return_a_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $rental = Rental::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'rented',
        ]);

        $response = $this->actingAs($user, 'api')->postJson('/api/v1/books/return', [
            'book_id' => $book->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('rentals', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'returned',
        ]);
    }

    /**
     * Test rental history.
     */
    public function test_user_can_view_rental_history()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $rental = Rental::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'rented',
        ]);

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/rentals/history');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'book_id' => $book->id,
            ]);
    }

    public function test_user_cannot_rent_same_book_twice()
    {
        // Create a user and a book
        $user = User::factory()->create();
        $book = Book::factory()->create();

        // Rent the book for the user
        Rental::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'rented', // Assuming 'rented' means the user hasn't returned the book yet
        ]);

        // Attempt to rent the same book again
        $response = $this->actingAs($user, 'api')
                         ->postJson('/api/v1/books/rent', ['book_id' => $book->id]);

        // Assert that the request is rejected due to validation error
        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'error',
                     "message" => "You have already rented this book and have not returned it yet.",
                     'errors' => [
                         'book' => ['You have already rented this book and have not returned it yet.']
                     ]
                 ]);
    }
}