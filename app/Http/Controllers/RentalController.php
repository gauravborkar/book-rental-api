<?php

namespace App\Http\Controllers;

use App\Services\RentalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(name="Rental", description="Operations related to book rentals")
 */
class RentalController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    /**
     * Rent a book for a user.
     * 
     * @OA\Post(
     *     path="/api/v1/books/rent",
     *     summary="Rent a book",
     *     description="Rent a book for a specific user",
     *     operationId="rentBook",
     *     tags={"Rental"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"book_id", "user_id"},
     *             @OA\Property(property="book_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book rented successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book rented successfully"),
     *             
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid input")
     *         )
     *     )
     * )
     */
    public function rent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $result = $this->rentalService->rentBook(auth()->user()->id, $request->book_id);

            return response()->json([
                'status' => 'success',
                'message' => 'Book rented successfully',
                'data' => $result
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/books/return",
     *     summary="Return a rented book",
     *     description="Return a rented book for a specific user",
     *     operationId="returnBook",
     *     tags={"Rental"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"book_id", "user_id"},
     *             @OA\Property(property="book_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book returned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book returned successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid input")
     *         )
     *     )
     * )
     */
    public function returnBook(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $result = $this->rentalService->returnBook(auth()->user()->id, $request->book_id);
        
        return response()->json($result);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/rentals/history",
     *     summary="Get rental history for a user",
     *     description="Return a rented book for a specific user",
     *     operationId="rentalHistory",
     *     tags={"Rental"},
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Book returned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book returned successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid input")
     *         )
     *     )
     * )
     */
    public function rentalHistory(): JsonResponse
    {
        $history = $this->rentalService->getRentalHistoryForUser(auth()->user()->id);
        return response()->json([
            'status' => 'success',
            'rental_history' => $history
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/books/{id}/rental-history",
     *     summary="Get rental history for a book",
     *     description="Retrieve the rental history for a specific book by its ID",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the book",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rental history retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="rental_history", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="John Doe")
     *                     ),
     *                     @OA\Property(property="rented_at", type="string", format="date-time", example="2024-01-01 12:00:00"),
     *                     @OA\Property(property="return_date", type="string", format="date-time", example="2024-01-14 12:00:00"),
     *                     @OA\Property(property="status", type="string", example="returned")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function rentalHistoryForBook($id): JsonResponse
    {
        $rentalHistory = $this->rentalService->getRentalHistoryForBook($id);

        return response()->json([
            'status' => 'success',
            'rental_history' => $rentalHistory
        ], 200);
    }
}