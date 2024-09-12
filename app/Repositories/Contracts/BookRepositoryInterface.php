<?php

namespace App\Repositories\Contracts;

interface BookRepositoryInterface
{
    public function searchBooks($name, $genre);

    public function getMostOverdueBook();

    public function getMostPopularBook();

    public function getLeastPopularBook();
}