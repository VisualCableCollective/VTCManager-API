<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Socialite\Facades\Socialite;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /*
     * VALUE
     * id                       int                 auto
     * current_game_running     string              nullable
     * last_client_update       datetime            nullable
     * PositionX                double              nullable
     * PositionY                double              nullable
     * PositionZ                double              nullable
     * OrientationHeading       float               nullable
     * current_company_id       int                 nullable
     * created_at               datetime            auto
     * updated_at               datetime            auto
     * company_id               unsignedInteger     nullable
     * latest_vcc_api_token     longText            required
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'bank_balance'.
        'company_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    protected $attributes = [
        'bank_balance' => 0,
        'latest_vcc_api_token' => ""
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function job_applications()
    {
        return $this->hasMany(JobApplication::class, "applicant_id");
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    /**
     * Returns the username of the user
     * @param $userID int
     * @param $VCC_API_AuthToken string valid VCC API auth token
     * @return mixed
     */
    public static function getUsername(int $userID, string $VCC_API_AuthToken){

         return Cache::remember('vcc-username-'.$userID, 300 , function () use($VCC_API_AuthToken, $userID) {
             $response = Http::withHeaders(
                 [
                     'Authorization' => 'Bearer ' . $VCC_API_AuthToken,
                     'Accept' => 'application/json',
                 ]
             )->get('https://vcc-online.eu/api/user/' . $userID);

             if($response->status() != 200)
                 return "n/a";

             return $response->json()["username"];
        });
    }

    public function license_key()
    {
        return $this->hasOne(LicenseKey::class);
    }
}
