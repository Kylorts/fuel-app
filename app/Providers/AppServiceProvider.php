<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Order;
use App\Policies\InvoicePolicy;
use App\Policies\OrderPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureRateLimiters();

        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);

        // Allow InvoicePolicy::create to be checked against Order model
        Gate::define('create-invoice', function ($user, Order $order) {
            return (new InvoicePolicy)->create($user, $order);
        });
    }

    private function configureRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email').'|'.$request->ip());
        });
    }
}
