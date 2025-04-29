<?php

namespace App\Providers;

use App\Contracts\TaskServiceInterface;
use App\Services\TaskService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding the interface to the concrete implementation
        $this->app->bind(TaskServiceInterface::class, TaskService::class);

        // Si tuvieras un CategoryService, también lo enlazarías aquí:
        // $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
