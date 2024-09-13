<?php

namespace App\Repositories\Contracts;

interface BookRepositoryInterface
{
    public function searchBooks(array $filters);

    public function getMostOverdueBook();

    public function getMostPopularBook();

    public function getLeastPopularBook();
}