<?php

namespace LuanFreitasDev\LivewireDataTables\Providers;

use Illuminate\Support\ServiceProvider;
use LuanFreitasDev\LivewireDataTables\Commands\DataTableCommand;

class DataTableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([DataTableCommand::class]);
        }

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'livewire-datatables');

        $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/laravel-datatables')], 'datatable-views');
    }

    public function register()
    {
    }
}
