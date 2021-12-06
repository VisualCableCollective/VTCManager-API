<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\FinishJobRequest;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\WebAppRequest;
use App\Models\Cargo;
use App\Models\City;
use App\Models\GameItemTranslation;
use App\Models\InGameCompany;
use App\Models\TruckManufacturer;
use App\Models\TruckModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Returns a paginated list of all jobs of the authenticated user
     * @param WebAppRequest $request
     * @return mixed
     */
    public function index(WebAppRequest $request)
    {
        $validatedRequest = $request->validated();

        if (!isset($validatedRequest["language_code"]))
            $validatedRequest["language_code"] = "en";

        return $request->user()->jobs()->latest()->with([
            'truck_model',
            'truck_model.truck_manufacturer',
            'city_departure', 'city_destination',
            'company_departure',
            'company_destination',
            'cargo',
            'cargo.game_item_translation' => function ($q) use ($validatedRequest) {
                $q->where('language_code', '=', $validatedRequest["language_code"])->orWhere('language_code', '=', "en");
            }
        ])->paginate(5);
    }

    /**
     * Returns detailed information about the job
     * @param WebAppRequest $request
     * @param $id
     * @return mixed
     */
    public function show(WebAppRequest $request, $id)
    {
        $validatedRequest = $request->validated();

        if (!isset($validatedRequest["language_code"]))
            $validatedRequest["language_code"] = "en";

        $job = Job::with([
            'truck_model',
            'truck_model.truck_manufacturer',
            'city_departure', 'city_destination',
            'company_departure',
            'company_destination',
            'cargo',
            'user',
            'cargo.game_item_translation' => function ($q) use ($validatedRequest) {
                $q->where('language_code', '=', $validatedRequest["language_code"])->orWhere('language_code', '=', "en");
            }
        ])->findorfail($id);

        $data = $job->job_data_entries()->get();
        $count = $data->count();
        if($count > 60){
            //reduce models
            $delta = floor($count/60);
            for($currentStage = 0; $currentStage < $count; $currentStage = $currentStage + $delta){
                $i = $currentStage + 1;
                while($i < $currentStage + $delta && $i < $count){
                    $data[$i]->delete();
                    $i++;
                }
            }
        }
        $job["job_data_entries"] = $job->job_data_entries()->get();
        return $job;
    }

    /**
     * Stores the job in the database
     * @param StoreJobRequest $request
     * @return mixed
     */
    public function store(StoreJobRequest $request)
    {
        $requestData = $request->validated();

        //check if job already exists
        $latest_job = $request->user()->jobs()->latest()->first();
        if ($latest_job != null) {
            if (
                $latest_job->cargo_id == $requestData["cargo_id"] &&
                $latest_job->cargo_mass == (float)$requestData["cargo_id"] &&
                $latest_job->city_departure_id == $requestData["city_departure_id"] &&
                $latest_job->company_departure_id == $requestData["company_departure_id"] . "." . $requestData["city_departure_id"] &&
                $latest_job->city_destination_id == $requestData["city_destination_id"] &&
                $latest_job->company_destination_id == $requestData["company_destination_id"] . "." . $requestData["city_destination_id"]
            )
                return $latest_job->id;
        }

        if (!$request->user()->company) {
            return ["success" => false];
        }

        $Job = $request->user()->jobs()->create([
            //Damage
            'truck_cabin_damage_at_start' => (float)$requestData["truck_cabin_damage_at_start"],
            'truck_chassis_damage_at_start' => (float)$requestData["truck_chassis_damage_at_start"],
            'truck_engine_damage_at_start' => (float)$requestData["truck_engine_damage_at_start"],
            'truck_transmission_damage_at_start' => (float)$requestData["truck_transmission_damage_at_start"],
            'truck_wheels_avg_damage_at_start' => (float)$requestData["truck_wheels_avg_damage_at_start"],

            //Trailer
            'trailer_avg_damage_chassis_at_start' => (float)$requestData["trailer_avg_damage_chassis_at_start"],
            'trailer_avg_damage_wheels_at_start' => (float)$requestData["trailer_avg_damage_wheels_at_start"],

            //Additional
            'market_id' => $requestData["market_id"],
            'special_job' => $requestData["special_job"],
            'job_ingame_started' => $requestData["job_ingame_started"],
            'job_ingame_deadline' => $requestData["job_ingame_deadline"],
            'income' => ((int)$requestData["job_income"]) * 0.5,
            'company_id' => $request->user()->company->id,

            //Cargo
            'cargo_mass' => (float)$requestData["cargo_mass"],

            //Route data
            'planned_distance_km' => $requestData["planned_distance_km"]
        ]);

        if ($requestData["language_code"] != null) {
            GameItemTranslation::firstOrCreate(
                ["id" => "cargo." . $requestData["cargo_id"], "language_code" => $requestData["language_code"]],
                ["value" => $requestData["cargo_name"]]
            );
        }

        Cargo::firstOrCreate(
            ["id" => $requestData["cargo_id"]],
            ["game_item_translation_id" => "cargo." . $requestData["cargo_id"]],
        );
        $Job->cargo_id = $requestData["cargo_id"];

        //check if city departure exists
        City::firstOrCreate(
            ["id" => $requestData["city_departure_id"]],
            ["name" => $requestData["city_departure_name"]],
        );
        $Job->city_departure_id = $requestData["city_departure_id"];

        //check if company departure exists
        InGameCompany::firstOrCreate(
            ["id" => $requestData["company_departure_id"] . "." . $requestData["city_departure_id"]],
            ["name" => $requestData["company_departure_name"], "city_id" => $requestData["city_departure_id"]],
        );
        $Job->company_departure_id = $requestData["company_departure_id"] . "." . $request["city_departure_id"];

        //check if city destination exists
        City::firstOrCreate(
            ["id" => $requestData["city_destination_id"]],
            ["name" => $requestData["city_destination_name"]],
        );
        $Job->city_destination_id = $requestData["city_destination_id"];

        //check if company destination exists
        InGameCompany::firstOrCreate(
            ["id" => $requestData["company_destination_id"] . "." . $requestData["city_destination_id"]],
            ["name" => $requestData["company_destination_name"], "city_id" => $requestData["city_destination_id"]],
        );
        $Job->company_destination_id = $requestData["company_destination_id"] . "." . $request["city_destination_id"];

        // TRUCK DATA
        //check if truck model exists
        if (!TruckModel::find($requestData["truck_model_id"])) {
            $TruckModel = $Job->truck_model()->create([
                "id" => $requestData["truck_model_id"],
                "name" => $requestData["truck_model_name"],
            ]);
            if (!TruckManufacturer::find($requestData["truck_model_manufacturer_id"])) {
                $TruckModel->truck_manufacturer()->create([
                    "id" => $requestData["truck_model_manufacturer_id"],
                    "name" => $requestData["truck_model_manufacturer_name"],
                ]);
            }

            // we have to set the ids manually because otherwise its not working for some reason
            $TruckModel->truck_manufacturer_id = $requestData["truck_model_manufacturer_id"];
            $TruckModel->save();
            $Job->truck_model_id = $requestData["truck_model_id"];
        } else {
            $Job->truck_model_id = $requestData["truck_model_id"];
        }

        $Job->save();
        return ["success" => true, "id" => $Job->id];
    }

    /** Finish the job successfully (mark job as delivered)
     * @param FinishJobRequest $request
     * @return mixed
     */
    public function delivered(FinishJobRequest $request, $id)
    {
        $validatedRequest = $request->validated();

        $job = Job::find($id);
        if ($job->user->id != $request->user()->id)
            abort(401);
        $job->status = "delivered";
        $job->remaining_delivery_time = $validatedRequest["remaining_delivery_time"];
        $job->remaining_distance = (float) $validatedRequest["remaining_distance"];
        $job->cargo_damage = (float) $validatedRequest["cargo_damage"];
        $job->truck_cabin_damage_at_end = (float) $validatedRequest["truck_cabin_damage_at_end"];
        $job->truck_chassis_damage_at_end = (float) $validatedRequest["truck_chassis_damage_at_end"];
        $job->truck_engine_damage_at_end = (float) $validatedRequest["truck_engine_damage_at_end"];
        $job->truck_transmission_damage_at_end = (float) $validatedRequest["truck_transmission_damage_at_end"];
        $job->truck_wheels_avg_damage_at_end = (float) $validatedRequest["truck_wheels_avg_damage_at_end"];
        $job->trailer_avg_damage_chassis_at_end = (float) $validatedRequest["trailer_avg_damage_chassis_at_end"];
        $job->trailer_avg_damage_wheels_at_end = (float) $validatedRequest["trailer_avg_damage_wheels_at_end"];
        $job->save();

        $user = User::find($request->user()->id);
        $user->bank_balance = $user->bank_balance + $job->income;
        $user->save();
        return response("", 204);
    }

    /**
     * Cancels the job
     * @param FinishJobRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function cancelled(FinishJobRequest $request, $id)
    {
        $validatedRequest = $request->validated();

        $job = Job::find($id);
        if ($job->user->id != $request->user()->id)
            abort(401);
        $job->status = "cancelled";
        $job->remaining_delivery_time = $validatedRequest["remaining_delivery_time"];
        $job->remaining_distance = (float) $validatedRequest["remaining_distance"];
        $job->cargo_damage = (float) $validatedRequest["cargo_damage"];
        $job->truck_cabin_damage_at_end = (float) $validatedRequest["truck_cabin_damage_at_end"];
        $job->truck_chassis_damage_at_end = (float) $validatedRequest["truck_chassis_damage_at_end"];
        $job->truck_engine_damage_at_end = (float) $validatedRequest["truck_engine_damage_at_end"];
        $job->truck_transmission_damage_at_end = (float) $validatedRequest["truck_transmission_damage_at_end"];
        $job->truck_wheels_avg_damage_at_end = (float) $validatedRequest["truck_wheels_avg_damage_at_end"];
        $job->trailer_avg_damage_chassis_at_end = (float) $validatedRequest["trailer_avg_damage_chassis_at_end"];
        $job->trailer_avg_damage_wheels_at_end = (float) $validatedRequest["trailer_avg_damage_wheels_at_end"];
        $job->save();
        return response("", 204);
    }
}
