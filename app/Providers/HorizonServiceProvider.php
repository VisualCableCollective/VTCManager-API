<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
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
            $s2sc_token = config('s2sc.token');
            if (!request()->session()->has('horizon_authenticated') || $s2sc_token == null) {
                if (!request()->has('token')) return false;

                $validatedRequest = request()->validate([
                    'token' => 'required|uuid'
                ]);

                // validate token
                $response = Http::withHeaders([
                    'S2SC-Token' => $s2sc_token
                ])->asJson()->acceptJson()
                    ->post('https://vcc-online.eu/api/s2sc/handoff-token/check', [
                        'service' => 'vtcmanager',
                        'tool' => 'horizon',
                        'token' => $validatedRequest['token'],
                        'user_ip' => request()->ip()
                    ]);

                if ($response->status() !== 204) return false;

                request()->session()->put('horizon_authenticated', true);

                redirect(config('horizon.path'));
            }

            return config('s2sc.token') != null && request()->session()->get('horizon_authenticated') == true;
        });
    }
}
