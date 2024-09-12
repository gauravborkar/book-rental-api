<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RentalService;

class MarkOverdueRentals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark rentals as overdue if they have not been returned within 2 weeks';

    protected $rentalService;
    /**
     * Create a new command instance.
     */
    public function __construct(RentalService $rentalService)
    {
        parent::__construct();
        $this->rentalService = $rentalService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Use the service to mark overdue rentals
        $count = $this->rentalService->markOverdueRentals();

        $this->info($count . ' rentals marked as overdue, and notifications sent.');
    }
}