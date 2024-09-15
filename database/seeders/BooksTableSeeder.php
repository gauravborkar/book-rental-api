<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            ['Title' => 'The Great Gatsby', 'Author' => 'F. Scott Fitzgerald', 'ISBN' => '9780743273565', 'Genre' => 'Classics'],
            ['Title' => 'To Kill a Mockingbird', 'Author' => 'Harper Lee', 'ISBN' => '9780060935467', 'Genre' => 'Classics'],
            ['Title' => '1984', 'Author' => 'George Orwell', 'ISBN' => '9780451524935', 'Genre' => 'Dystopian'],
            ['Title' => 'Pride and Prejudice', 'Author' => 'Jane Austen', 'ISBN' => '9780141199078', 'Genre' => 'Romance'],
            ['Title' => 'The Catcher in the Rye', 'Author' => 'J.D. Salinger', 'ISBN' => '9780316769488', 'Genre' => 'Classics'],
            ['Title' => 'The Hobbit', 'Author' => 'J.R.R. Tolkien', 'ISBN' => '9780547928227', 'Genre' => 'Fantasy'],
            ['Title' => 'Fahrenheit 451', 'Author' => 'Ray Bradbury', 'ISBN' => '9781451673319', 'Genre' => 'Science Fiction'],
            ['Title' => 'The Book Thief', 'Author' => 'Markus Zusak', 'ISBN' => '9780375842207', 'Genre' => 'Historical Fiction'],
            ['Title' => 'Moby-Dick', 'Author' => 'Herman Melville', 'ISBN' => '9781503280786', 'Genre' => 'Classics'],
            ['Title' => 'War and Peace', 'Author' => 'Leo Tolstoy', 'ISBN' => '9781400079988', 'Genre' => 'Historical Fiction'],
        ]);
    }
}