<?php

namespace App\Services;

use App\Repositories\Contracts\BookRepositoryInterface;

class BookService
{
    protected $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function searchBooks($name, $genre)
    {
        return $this->bookRepository->searchBooks($name, $genre);
    }
}