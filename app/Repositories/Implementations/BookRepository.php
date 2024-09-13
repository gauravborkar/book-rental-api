<?php

namespace App\Repositories\Implementations;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    /**
     * Get books with optional filters (title and genre).
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchBooks(array $filters)
    {
        $query = Book::query();

        // Apply title filter if provided
        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        // Apply genre filter if provided
        if (!empty($filters['genre'])) {
            $query->where('genre', 'like', '%' . $filters['genre'] . '%');
        }

        // Return the result
        return $query->get();
    }

    /**
     * Get the most overdue book based on rentals that are overdue.
     */
    public function getMostOverdueBook()
    {
        return Rental::with('book') 
            ->where('status', 'overdue') 
            ->select('book_id', DB::raw('COUNT(book_id) as overdue_count'))
            ->groupBy('book_id')
            ->orderByDesc('overdue_count')
            ->first()
            ->book;
    }

    /**
     * Get the most popular book based on the highest number of rentals.
     */
    public function getMostPopularBook()
    {
        return Book::withCount('rentals')
            ->orderByDesc('rentals_count')
            ->first();
    }

    /**
     * Get the least popular book based on the lowest number of rentals.
     */
    public function getLeastPopularBook()
    {
        return Book::withCount('rentals')
            ->orderBy('rentals_count', 'asc')
            ->first();
    }
}