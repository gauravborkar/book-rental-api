<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching all books without any filters.
     */
    public function test_can_fetch_all_books()
    {
        // Create some books in the database
        Book::factory()->create(['name' => 'Clean Code', 'genre' => 'Programming']);
        Book::factory()->create(['name' => 'Refactoring', 'genre' => 'Programming']);

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
        Book::factory()->create(['name' => 'Clean Code', 'genre' => 'Programming']);
        Book::factory()->create(['name' => 'Refactoring', 'genre' => 'Programming']);

        // Create a user and authenticate the request
        $user = User::factory()->create();

        // Act as the authenticated user and fetch books with a name filter
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/books?name=Clean');

        // Assert that only the "Clean Code" book is returned
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Clean Code'])
            ->assertJsonMissing(['name' => 'Refactoring']);
    }

    /**
     * Test fetching books with a genre filter.
     */
    public function test_can_fetch_books_with_genre_filter()
    {
        // Create some books in the database
        Book::factory()->create(['name' => 'Clean Code', 'genre' => 'Programming']);
        Book::factory()->create(['name' => 'Design Patterns', 'genre' => 'Architecture']);

        // Create a user and authenticate the request
        $user = User::factory()->create();

        // Act as the authenticated user and fetch books with a genre filter
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/books?genre=Programming');

        // Assert that only the "Programming" books are returned
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Clean Code'])
            ->assertJsonMissing(['name' => 'Design Patterns']);
    }

    /**
     * Test fetching books with both filters.
     */
    public function test_can_fetch_books_with_both_filters()
    {
        // Create some books in the database
        Book::factory()->create(['name' => 'Clean Code', 'genre' => 'Programming']);
        Book::factory()->create(['name' => 'Design Patterns', 'genre' => 'Architecture']);

        // Create a user and authenticate the request
        $user = User::factory()->create();

        // Act as the authenticated user and fetch books with both filters
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/books?name=Clean&genre=Programming');

        // Assert that only "Clean Code" is returned with the correct genre
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Clean Code'])
            ->assertJsonMissing(['name' => 'Design Patterns']);
    }
}