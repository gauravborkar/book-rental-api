<?php

namespace App\Repositories\Implementations;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function searchBooks($name, $genre)
    {
        return Book::where('name', 'LIKE', "%{$name}%")
                   ->where('genre', $genre)
                   ->get();
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