<?php

namespace App\Providers;

use App\Console\Commands\AuditPemberitahuanPenyediaSyncCommand;
use App\Contracts\PenyediaRepositoryInterface;
use App\Repositories\PenyediaRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PenyediaRepositoryInterface::class,
            PenyediaRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AuditPemberitahuanPenyediaSyncCommand::class,
            ]);
        }
    }
}
