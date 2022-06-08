<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\City;
use App\Models\InGameCompany;
use App\Models\Job;
use App\Models\TruckModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'truck_model_id' => TruckModel::inRandomOrder()->first()->id,
            'city_departure_id' => City::inRandomOrder()->first()->id,
            'city_destination_id' => City::inRandomOrder()->first()->id,
            'company_departure_id' => InGameCompany::inRandomOrder()->first()->id,
            'company_destination_id' => InGameCompany::inRandomOrder()->first()->id,
            'cargo_id' => Cargo::inRandomOrder()->first()->id,
            'cargo_mass' => $this->faker->numberBetween(1000, 10000),
            'planned_distance_km' => $this->faker->numberBetween(100, 10000),
            'special_job' => $this->faker->boolean(),
            'job_ingame_started' => Carbon::now(),
            'job_ingame_deadline' => Carbon::now()->addHours(12),
            'market_id' => 1,
            'truck_cabin_damage_at_start' => 0,
            'truck_chassis_damage_at_start' => 0,
            'truck_engine_damage_at_start' => 0,
            'truck_transmission_damage_at_start' => 0,
            'truck_wheels_avg_damage_at_start' => 0,
            'trailer_avg_damage_chassis_at_start' => 0,
            'trailer_avg_damage_wheels_at_start' => 0,
            'income' => $this->faker->numberBetween(1000, 10000)
        ];
    }

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;
}
