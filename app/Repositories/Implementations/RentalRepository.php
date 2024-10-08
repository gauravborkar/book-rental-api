<?php

namespace App\Repositories\Implementations;

use App\Models\Rental;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\RentalRepositoryInterface;
use Carbon\Carbon;

class RentalRepository implements RentalRepositoryInterface
{
    /**
     * Check if the user has already rented this book and has not returned it.
     *
     * @param int $userId
     * @param int $bookId
     * @return bool
     */
    public function isBookAlreadyRentedByUser($userId, $bookId)
    {
        return Rental::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('status', 'rented') // Assuming 'rented' means the book is currently rented
            ->exists();
    }

    /**
     * Rent a book for the user.
     */
    public function rentBook(int $userId, int $bookId, $rentedAt, $returnDate)
    {
        $rental = Rental::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'rented_at' => $rentedAt,
            'return_date' => $returnDate,
            'status' => 'rented'
        ]);

        return [
            'message' => 'Book rented successfully',
            'rental' => $rental
        ];
    }

    /**
     * Return a rented book for the user.
     */
    public function returnBook(int $userId, int $bookId)
    {
        $rental = Rental::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->whereNull('returned_at')
            ->first();

        if (!$rental) {
            return [
                'status' => 'error',
                'message' => 'Rental not found or already returned'
            ];
        }

        $rental->returned_at = now();
        $rental->status = 'returned';
        $rental->save();

        return [
            'status' => 'success',
            'message' => 'Book returned successfully',
            'rental' => $rental
        ];
    }

    /**
     * Get the rental history for a specific user.
     */
    public function getRentalHistoryByUserId(int $userId)
    {
        return Rental::with('book') // Load related book information
            ->where('user_id', $userId) // Filter by the user's ID
            ->orderBy('rented_at', 'desc') // Order by the rental date, newest first
            ->get() // Get all rental records
            ->toArray(); // Convert to array to return in a JSON-friendly format
    }

    /**
     * Get rental history for a specific book by book ID.
     *
     * @param int $bookId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRentalHistoryByBookId(int $bookId)
    {
        return Rental::with('user')
            ->where('book_id', $bookId)
            ->orderBy('rented_at', 'desc')
            ->get();
    }
    
    /**
     * Get rentals that are overdue (not returned within 2 weeks).
     */
    public function getOverdueRentals()
    {
        return Rental::where('return_date', '<', Carbon::now())
            ->whereNull('returned_at') // Ensure the book hasn't been returned
            ->get();
    }

    /**
     * Mark a rental as overdue.
     */
    public function markAsOverdue($rental)
    {
        $rental->status = 'overdue';
        $rental->save();
    }
}