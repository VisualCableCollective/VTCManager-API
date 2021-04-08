<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinishJobRequest extends FormRequest
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
            'remaining_delivery_time' => 'required|date',
            'remaining_distance' => 'required',
            'cargo_damage' => 'required',
            'truck_cabin_damage_at_end' => 'required',
            'truck_chassis_damage_at_end' => 'required',
            'truck_engine_damage_at_end' => 'required',
            'truck_transmission_damage_at_end' => 'required',
            'truck_wheels_avg_damage_at_end' => 'required',
            'trailer_avg_damage_chassis_at_end' => 'required',
            'trailer_avg_damage_wheels_at_end' => 'required',
        ];
    }
}
