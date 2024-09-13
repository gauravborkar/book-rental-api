<?php

namespace App\Repositories\Contracts;

interface RentalRepositoryInterface
{
    public function rentBook(int $userId, int $bookId, $rentedAt, $returnDate);

    public function returnBook(int $userId, int $bookId);

    public function getRentalHistoryByUserId(int $userId);

    public function getRentalHistoryByBookId(int $bookId);
    
    public function getOverdueRentals(); 

    public function markAsOverdue($rental); 
}