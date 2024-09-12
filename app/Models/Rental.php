<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id', 'rented_at', 'return_date', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the rental is overdue.
     */
    public function isOverdue()
    {
        return $this->return_date < Carbon::now() && is_null($this->returned_at);
    }

    /**
     * Scope to get only overdue rentals.
     */
    public function scopeOverdue($query)
    {
        return $query->where('return_date', '<', Carbon::now())
                     ->whereNull('returned_at');
    }
}
