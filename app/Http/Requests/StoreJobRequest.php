<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //cargo data
            'cargo_id' => 'required|string',
            'cargo_name' => 'required|string',
            'cargo_mass' => 'required',

            //navigation data
            'planned_distance_km' => 'required|integer',
            'city_departure_id' => 'required|string',
            'city_departure_name' => 'required|string',
            'company_departure_id' => 'required|string',
            'company_departure_name' => 'required|string',
            'city_destination_id' => 'required|string',
            'city_destination_name' => 'required|string',
            'company_destination_id' => 'required|string',
            'company_destination_name' => 'required|string',

            //truck data
            'truck_model_id' => 'required|string',
            'truck_model_name'=> 'required|string',
            'truck_model_manufacturer_id' => 'required|string',
            'truck_model_manufacturer_name' => 'required|string',
            'truck_cabin_damage_at_start' => 'required',
            'truck_chassis_damage_at_start' => 'required',
            'truck_engine_damage_at_start' => 'required',
            'truck_transmission_damage_at_start' => 'required',
            'truck_wheels_avg_damage_at_start' => 'required',

            //trailer data
            'trailer_avg_damage_chassis_at_start' => 'required',
            'trailer_avg_damage_wheels_at_start' => 'required',

            //additional data
            'market_id' => 'required|string',
            'special_job' => 'boolean',
            'job_ingame_started' => 'required|date',
            'job_ingame_deadline' => 'required|date',
            'job_income' => 'required|integer',
            'language_code' => 'string|nullable',
        ];
    }
}
