<?php

namespace App\Providers;

use App\Adapters\DomPdfAdapter;
use App\Adapters\LaravelInvoicesAdapter;
use App\Interfaces\InvoiceGenerator;
use Illuminate\Support\ServiceProvider;

class InvoiceGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(InvoiceGenerator::class, LaravelInvoicesAdapter::class);
    }

    public function boot(): void
    {
        //
    }
}
