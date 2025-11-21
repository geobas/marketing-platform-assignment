<?php

namespace App\Providers;

use App\Contracts\LeadRepositoryInterface;
use App\Models\Lead;
use App\Observers\LeadObserver;
use App\Repositories\LeadRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LeadRepositoryInterface::class, LeadRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Lead::observe(LeadObserver::class);
    }
}
