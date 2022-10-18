<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
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
        $s2sc_token = config('s2sc.token');

        if (empty($s2sc_token)) {
            Log::error("RemoveDeletedUsers: No VCC_S2SC_TOKEN specified in the env.");
            $this->fail();
            return;
        }

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            $response = Http::withHeaders([
                'S2SC-Token' => $s2sc_token
            ])->get('https://vcc-online.eu/api/s2sc/user/' . $user->id);

            if ($response->ok() && !$response->json()['success']) {
                $user->delete();
                $count++;
            }
        }

        Log::info("RemoveDeletedUsers: $count users have been deleted as they don't have a VCC account anymore.");
    }
}
