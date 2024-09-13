<?php

namespace App\Http\Controllers;

use App\Services\RentalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     *     path="/api/v1/rent",
     *     summary="Rent a book",
     *     description="Rent a book for a specific user",
     *     operationId="rentBook",
     *     tags={"Rental"},
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

        $result = $this->rentalService->rentBook(auth()->user()->id, $request->book_id);
        return response()->json($result);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/return",
     *     summary="Return a rented book",
     *     description="Return a rented book for a specific user",
     *     operationId="returnBook",
     *     tags={"Rental"},
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
     *     summary="View rental history",
     *     description="Retrieve the rental history for a specific user",
     *     operationId="viewRentalHistory",
     *     tags={"Rental"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Rental history retrieved successfully",
     *         
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function rentalHistory(): JsonResponse
    {
        $history = $this->rentalService->getRentalHistoryForUser(auth()->user()->id);
        return response()->json($history);
    }
}