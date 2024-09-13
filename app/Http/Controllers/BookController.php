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
        // Extract filters from query parameters
        $filters = $request->only(['name', 'genre']);

        // Call the service to retrieve filtered books
        $books = $this->bookService->searchBooks($filters);

        // Return the result as JSON
        return response()->json($books);
    }
}