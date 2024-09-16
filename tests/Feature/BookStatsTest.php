<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookStatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching book stats (most popular, least popular, most overdue).
     *
     * @return void
     */
    public function test_fetch_book_stats()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Create books
        $book1 = Book::factory()->create(['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '9780141199078', 'genre' => 'Romance']);
        $book2 = Book::factory()->create(['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'isbn' => '9780060935467', 'genre' => 'Classics']);


        // Create rentals for book1 (most rented, most overdue)
        Rental::factory()->count(10)->create([
            'book_id' => $book1->id,
            'user_id' => $user->id,
        ]);

        // Mark 5 of these rentals as overdue
        Rental::factory()->count(5)->create([
            'book_id' => $book1->id,
            'user_id' => $user->id,
            'status' => 'overdue'
        ]);

        // Create rentals for book2 (least rented)
        Rental::factory()->count(2)->create([
            'book_id' => $book2->id,
            'user_id' => $user->id,
        ]);

        // Act as the user and hit the API to get the stats
        $response = $this->actingAs($user, 'api')
                         ->getJson('/api/v1/books/stats');

        // Assert the response is successful
        // Assert the response is successful and returns the correct stats
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'most_popular_book' => [
                        'id' => $book1->id,
                        'title' => 'Pride and Prejudice',
                        'rental_count' => 15,
                    ],
                    'least_popular_book' => [
                        'id' => $book2->id,
                        'title' => 'To Kill a Mockingbird',
                        'rental_count' => 2,
                    ],
                    'most_overdue_book' => [
                        'id' => $book1->id,
                        'title' => 'Pride and Prejudice',
                        'overdue_count' => 5,
                    ]
                ]
            ]);
    }

    /**
     * Test fetching book stats when no books have rentals.
     *
     * @return void
     */
    public function test_fetch_book_stats_when_no_rentals_exist()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Create books without rentals
        Book::factory()->count(2)->create();

        // Act as the user and hit the API to get the stats
        $response = $this->actingAs($user, 'api')
                         ->getJson('/api/v1/books/stats');

        // Assert that the response returns a 404 since no books have rentals
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                        'most_popular_book' => null,
                        'least_popular_book' => null,
                        'most_overdue_book' => null
                     ]
                 ]);
    }
}