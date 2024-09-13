<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Rental;

class OverdueRentalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $rental;

    /**
     * Create a new message instance.
     */
    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Rental is Overdue')
                    ->view('emails.overdue_rental')
                    ->with([
                        'bookTitle' => $this->rental->book->title,
                        'rentalDate' => $this->rental->rented_at,
                        'dueDate' => $this->rental->return_date,
                    ]);
    }
}