<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Books", description="Operations related to books")
 */
class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/books",
     *     summary="Search books",
     *     description="Retrieve a list of books with optional filters for name and genre",
     *     operationId="searchBooks",
     *     tags={"Books"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Filter by book name",
     *         required=false,
     *         @OA\Schema(type="string", example="Clean Code")
     *     ),
     *     @OA\Parameter(
     *         name="genre",
     *         in="query",
     *         description="Filter by book genre",
     *         required=false,
     *         @OA\Schema(type="string", example="Programming")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Books retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Books retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No books found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No books found")
     *         )
     *     )
     * )
     */
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