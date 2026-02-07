<?php

namespace App\Providers;

use App\Models\Customer;
use App\Policies\CustomerPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

    protected $policies=[
        Customer::class => CustomerPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define("view-customer",function($user,Customer $customer){
            return $user->id===$customer->user_id;
        });
    }
}
