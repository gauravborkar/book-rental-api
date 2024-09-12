<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Implementations\BookRepository;
use App\Repositories\Contracts\RentalRepositoryInterface;
use App\Repositories\Implementations\RentalRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(
        //     \App\Repositories\Contracts\BookRepositoryInterface::class,
        //     \App\Repositories\Implementations\BookRepository::class,
        //     \App\Repositories\Contracts\RentalRepositoryInterface::class,
        //     \App\Repositories\Implementations\RentalRepository::class
        // );

        // Binding the interface to its implementation
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(RentalRepositoryInterface::class, RentalRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
