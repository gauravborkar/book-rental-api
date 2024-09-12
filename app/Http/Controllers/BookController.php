<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function search(Request $request)
    {
        $name = $request->get('name');
        $genre = $request->get('genre');

        return $this->bookService->searchBooks($name, $genre);
    }
}