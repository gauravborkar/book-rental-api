<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // References users table
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); // References books table
            $table->timestamp('rented_at'); // When the book was rented
            $table->timestamp('return_date'); // When the book is due to be returned
            $table->timestamp('returned_at')->nullable(); // When the book was actually returned
            $table->enum('status', ['rented', 'returned', 'overdue'])->default('rented'); // Rental status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('rentals');
    }
}