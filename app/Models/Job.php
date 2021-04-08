<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /*
     * VALUES
     * id                                   integer             auto
     * user_id                              integer             required
     * truck_model_id                       string              nullable
     * city_departure_id                    string              required
     * city_destination_id                  string              required
     * company_departure_id                 string              required
     * company_destination_id               string              required
     * cargo_id                             string              required
     * cargo_mass                           float               required
     * planned_distance_km                  integer             required
     * special_job                          bool                default     false
     * job_ingame_started                   datetime            required
     * job_ingame_deadline                  datetime            required
     * market_id                            string              required
     * truck_cabin_damage_at_start          float               required
     * truck_chassis_damage_at_start        float               required
     * truck_engine_damage_at_start         float               required
     * truck_transmission_damage_at_start   float               required
     * truck_wheels_avg_damage_at_start     float               required
     * trailer_avg_damage_chassis_at_start  float               required
     * trailer_avg_damage_wheels_at_start   float               required
     * truck_cabin_damage_at_end            float               nullable
     * truck_chassis_damage_at_end          float               nullable
     * truck_engine_damage_at_end           float               nullable
     * truck_transmission_damage_at_end     float               nullable
     * truck_wheels_avg_damage_at_end       float               nullable
     * trailer_avg_damage_chassis_at_end    float               nullable
     * trailer_avg_damage_wheels_at_end     float               nullable
     * remaining_delivery_time              datetime            nullable
     * remaining_distance                   float               nullable
     * cargo_damage                         float               nullable
     * status                               string              default     started
     * income                               int                 required
     * company_id                           unsignedInteger     nullable
     */

    protected $attributes = [
        'status' => "started",
        'special_job' => false,
        'truck_model_id' => null,
        'city_departure_id' => null,
        'city_destination_id' => null,
        'company_departure_id' => null,
        'company_destination_id' => null,
        'cargo_id' => null,
    ];

    protected $fillable = [
        //Damage
        'truck_cabin_damage_at_start',
        'truck_chassis_damage_at_start',
        'truck_engine_damage_at_start',
        'truck_transmission_damage_at_start',
        'truck_wheels_avg_damage_at_start',

        //Trailer
        'trailer_avg_damage_chassis_at_start',
        'trailer_avg_damage_wheels_at_start',

        //Additional
        'market_id',
        'special_job',
        'job_ingame_started',
        'job_ingame_deadline',
        'income',
        'company_id',

        //Cargo
        'cargo_mass',

        //Route data
        'planned_distance_km',

        'truck_model_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function truck_model(){
        return $this->belongsTo(TruckModel::class, "truck_model_id");
    }

    public function city_departure(){
        return $this->belongsTo(City::class,"city_departure_id");
    }

    public function city_destination(){
        return $this->belongsTo(City::class,"city_destination_id");
    }

    public function company_departure(){
        return $this->belongsTo(InGameCompany::class,"company_departure_id");
    }

    public function company_destination(){
        return $this->belongsTo(InGameCompany::class,"company_destination_id");
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function job_data_entries()
    {
        return $this->hasMany(JobDataEntry::class);
    }
}
