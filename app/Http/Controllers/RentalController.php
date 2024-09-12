<?php

namespace App\Http\Controllers;

use App\Services\RentalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    /**
     * Rent a book for the authenticated user.
     */
    public function rent(Request $request): JsonResponse
    {
        $this->validate($request, [
            'book_id' => 'required|exists:books,id'
        ]);

        $result = $this->rentalService->rentBook(auth()->user()->id, $request->book_id);
        return response()->json($result);
    }

    /**
     * Return a rented book for the authenticated user.
     */
    public function returnBook(Request $request): JsonResponse
    {
        $this->validate($request, [
            'book_id' => 'required|exists:books,id'
        ]);

        $result = $this->rentalService->returnBook(auth()->user()->id, $request->book_id);
        return response()->json($result);
    }

    /**
     * View the rental history for the authenticated user.
     */
    public function rentalHistory(): JsonResponse
    {
        $history = $this->rentalService->getRentalHistoryForUser(auth()->user()->id);
        return response()->json($history);
    }
}