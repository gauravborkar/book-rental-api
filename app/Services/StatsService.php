<?php

namespace App\Services;

use App\Repositories\Contracts\BookRepositoryInterface;

class StatsService
{
    protected $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Get stats for the most overdue, most popular, and least popular books.
     */
    public function getBookStats(): array
    {
        // Get the most overdue book
        $mostOverdueBook = $this->bookRepository->getMostOverdueBook();

        // Get the most popular book (highest rentals)
        $mostPopularBook = $this->bookRepository->getMostPopularBook();

        // Get the least popular book (lowest rentals)
        $leastPopularBook = $this->bookRepository->getLeastPopularBook();

        // If no books have been rented or overdue, return 404
        if (!$mostPopularBook && !$leastPopularBook && !$mostOverdueBook) {
            return [
                'status' => 'error',
                'message' => 'No stats available'
            ];
        }
        
        return [
            'most_popular_book' => $mostPopularBook,
            'least_popular_book' => $leastPopularBook,
            'most_overdue_book' => $mostOverdueBook
        ];
    }
}