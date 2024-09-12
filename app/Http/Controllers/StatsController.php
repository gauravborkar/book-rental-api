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
     * Get stats for the most overdue, most popular, and least popular books.
     */
    public function getStats(): JsonResponse
    {
        $stats = $this->statsService->getBookStats();
        return response()->json($stats);
    }
}