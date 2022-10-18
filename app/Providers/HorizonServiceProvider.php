<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');

        Horizon::night();
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewHorizon', function ($user = null) {
            if (!request()->session()->has('s2sc_access_code') || config('s2sc.token') == null) {
                if (!request()->has('accessCode') || request()->accessCode != config('s2sc.token')) return false;

                request()->session()->put('s2sc_access_code', request()->accessCode);
                request()->redirect(request()->fullUrlWithoutQuery('accessCode'));
            }

            return config('s2sc.token') != null && request()->session()->get('s2sc_access_code') == config('s2sc.token');
        });
    }
}
