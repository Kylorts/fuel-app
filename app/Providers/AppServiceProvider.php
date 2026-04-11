<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Order;
use App\Policies\InvoicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Invoice::class, InvoicePolicy::class);

        // Allow InvoicePolicy::create to be checked against Order model
        Gate::define('create-invoice', function ($user, Order $order) {
            return (new InvoicePolicy)->create($user, $order);
        });
    }
}
