<?php

namespace App\Http\Controllers;

use App\Services\StatsService;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    protected $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/rentals/stats",
     *     summary="Get the most overdue book",
     *     description="Stats to show the most overdue book, most popular and least popular",
     *     operationId="getStats",
     *     tags={"Statistics"},
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Stats retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Stats retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No overdue books found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No overdue books found")
     *         )
     *     )
     * )
     */
    public function getStats(): JsonResponse
    {
        $stats = $this->statsService->getBookStats();
        
        return response()->json([
            'status' => 'success',
            'data' => $stats
        ], 200);
    }
}