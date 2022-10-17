<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\VCC\VccAuthenticationFailedException;

class RemoveDeletedUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // I think that's unsafe, because it can only check if the API token is still valid.
        $count = 0;

        $users = User::all();
        foreach ($users as $user) {
            try {
                Socialite::driver('vcc')->stateless()->userFromToken($user->latest_vcc_api_token);
            } catch (VccAuthenticationFailedException $e) {
                $count++;
                echo $user->id;
            }
        }

        $msg = "RemoveDeletedUsers: $count users have an invalid token.";
        Log::info($msg);
        echo $msg;
    }
}
