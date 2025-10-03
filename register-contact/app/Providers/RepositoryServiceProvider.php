<?php

namespace App\Providers;

use App\Repositories\Contact\ContactInterface;
use App\Repositories\Contact\ContactRepository;
use App\Repositories\Student\StudentInterface;
use App\Repositories\Student\StudentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository interfaces to implementations
        $this->app->bind(ContactInterface::class, ContactRepository::class);
        $this->app->bind(StudentInterface::class, StudentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
