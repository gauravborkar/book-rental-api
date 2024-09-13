<?php

namespace App\Services;

use App\Repositories\Contracts\RentalRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueRentalNotification;

class RentalService
{
    protected $rentalRepository;

    public function __construct(RentalRepositoryInterface $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }

    /**
     * Rent a book for a user.
     */
    public function rentBook(int $userId, int $bookId): array
    {
        $rentedAt = Carbon::now();
        $returnDate = Carbon::now()->addWeeks(2); // Set return date to 2 weeks later

        return $this->rentalRepository->rentBook($userId, $bookId, $rentedAt, $returnDate);
    }

    /**
     * Return a book for a user.
     */
    public function returnBook(int $userId, int $bookId): array
    {
        return $this->rentalRepository->returnBook($userId, $bookId);
    }

    /**
     * Get the rental history for a user.
     */
    public function getRentalHistoryForUser(int $userId): array
    {
        return $this->rentalRepository->getRentalHistoryByUserId($userId);
    }

    /**
     * Get rental history for a book.
     *
     * @param int $bookId
     * @return mixed
     */
    public function getRentalHistoryForBook(int $bookId)
    {
        return $this->rentalRepository->getRentalHistoryByBookId($bookId);
    }

    /**
     * Mark overdue rentals if not returned within 2 weeks.
     * This method will be triggered by a scheduled command.
     */
    public function markOverdueRentals(): int
    {
        // Get rentals that are overdue from the repository
        $overdueRentals = $this->rentalRepository->getOverdueRentals();
        $count = 0;

        foreach ($overdueRentals as $rental) {
            // Mark each overdue rental
            $this->rentalRepository->markAsOverdue($rental);
            $count++;

            // Send email notification to the user
            Mail::to($rental->user->email)->send(new OverdueRentalNotification($rental));
        }

        return $count;
    }
}