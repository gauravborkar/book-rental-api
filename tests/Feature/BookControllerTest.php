<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Rental;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching all books without any filters.
     */
    public function test_can_fetch_all_books()
    {
        // Create some books in the database
        Book::factory()->create(['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '9780141199078', 'genre' => 'Romance']);
        Book::factory()->create(['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'isbn' => '9780060935467', 'genre' => 'Classics']);

        // Create a user and authenticate the request
        $user = User::factory()->create();

        // Act as the authenticated user and fetch all books
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/books');

        // Assert that the response contains both books
        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    /**
     * Test fetching books with a name filter.
     */
    public function test_can_fetch_books_with_name_filter()
    {
        // Create some books in the database
        Book::factory()->create(['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '9780141199078', 'genre' => 'Romance']);
        Book::factory()->create(['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'isbn' => '9780060935467', 'genre' => 'Classics']);

        // Create a user and authenticate the request
        $user = User::factory()->create();

        // Act as the authenticated user and fetch books with a name filter
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/books?title=Pride');

        // Assert that only the "Clean Code" book is returned
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Pride and Prejudice'])
            ->assertJsonMissing(['title' => 'To Kill a Mockingbird']);
    }

    /**
     * Test fetching books with a genre filter.
     */
    public function test_can_fetch_books_with_genre_filter()
    {
        // Create some books in the database
        Book::factory()->create(['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '9780141199078', 'genre' => 'Romance']);
        Book::factory()->create(['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'isbn' => '9780060935467', 'genre' => 'Classics']);

        // Create a user and authenticate the request
        $user = User::factory()->create();

        // Act as the authenticated user and fetch books with a genre filter
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/books?genre=Romance');

        // Assert that only the "Programming" books are returned
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Pride and Prejudice'])
            ->assertJsonMissing(['title' => 'To Kill a Mockingbird']);
    }

    /**
     * Test fetching books with both filters.
     */
    public function test_can_fetch_books_with_both_filters()
    {
        // Create some books in the database
        Book::factory()->create(['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '9780141199078', 'genre' => 'Romance']);
        Book::factory()->create(['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'isbn' => '9780060935467', 'genre' => 'Classics']);

        // Create a user and authenticate the request
        $user = User::factory()->create();

        // Act as the authenticated user and fetch books with both filters
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/books?title=Pride&genre=Romance');

        // Assert that only "Clean Code" is returned with the correct genre
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Pride and Prejudice'])
            ->assertJsonMissing(['title' => 'To Kill a Mockingbird']);
    }

    /**
     * Test fetching rental history for a book.
     *
     * @return void
     */
    public function test_can_fetch_rental_history_for_book()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a book
        $book = Book::factory()->create([
            'title' => 'Pride and Prejudice',
            'genre' => 'Romance',
            'author' => 'Jane Austen',
            'isbn' => '9780141199078',
            'quantity' => 10,
        ]);

        // Create rentals for the book
        Rental::factory()->create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'rented_at' => now()->subDays(10),
            'return_date' => now()->subDays(2),
            'status' => 'returned',
        ]);

        // Act as the user and fetch the rental history
        $this->actingAs($user, 'api')
            ->getJson('/api/v1/books/' . $book->id . '/rental-history')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'rental_history' => [
                    '*' => [
                        'id',
                        'user' => [
                            'id',
                            'name',
                        ],
                        'rented_at',
                        'return_date',
                        'status',
                    ],
                ],
            ]);
    }
}