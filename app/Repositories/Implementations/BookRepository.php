<?php

namespace App\Repositories\Implementations;

use App\Models\Book;
use App\Models\Rental;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        $rental = Rental::with('book') 
            ->where('status', 'overdue') 
            ->select('book_id', DB::raw('COUNT(book_id) as overdue_count'))
            ->groupBy('book_id')
            ->orderByDesc('overdue_count')
            ->first();
        
        // Return the related book if rental is found
        return $rental ? [
            'id' => $rental->book->id,
            'title' => $rental->book->title,
            'overdue_count' => $rental->overdue_count,
        ] : null;
    }

    /**
     * Get the most popular book based on the highest number of rentals.
     */
    public function getMostPopularBook()
    {
        $rental = Rental::select('book_id', DB::raw('COUNT(*) as rental_count'))
            ->groupBy('book_id')
            ->orderByDesc('rental_count')
            ->with('book')
            ->first();

        // Return the related book if rental is found
        return $rental ? [
            'id' => $rental->book->id,
            'title' => $rental->book->title,
            'rental_count' => $rental->rental_count,
        ] : null;
    }

    /**
     * Get the least popular book based on the lowest number of rentals.
     */
    public function getLeastPopularBook()
    {
        $rental = Rental::select('book_id', DB::raw('COUNT(*) as rental_count'))
            ->groupBy('book_id')
            ->orderBy('rental_count', 'asc')
            ->with('book')
            ->first();

        // Return the related book if rental is found
        return $rental ? [
            'id' => $rental->book->id,
            'title' => $rental->book->title,
            'rental_count' => $rental->rental_count,
        ] : null;
    }
}