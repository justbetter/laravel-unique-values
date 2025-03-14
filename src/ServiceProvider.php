<?php

namespace JustBetter\UniqueValues;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JustBetter\UniqueValues\Actions\DetermineUnique;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this
            ->registerConfig()
            ->registerActions();
    }

    protected function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__.'/../config/unique-values.php', 'unique-values');

        return $this;
    }

    protected function registerActions(): static
    {
        DetermineUnique::bind();

        return $this;
    }

    public function boot(): void
    {
        $this
            ->bootConfig()
            ->bootMigrations();
    }

    protected function bootConfig(): static
    {
        $this->publishes([
            __DIR__.'/../config/unique-values.php' => config_path('unique-values.php'),
        ], 'config');

        return $this;
    }

    protected function bootMigrations(): static
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        return $this;
    }
}
