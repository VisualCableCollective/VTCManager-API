<?php

namespace App\Http\Controllers\API\WebApp;

use App\Http\Requests\ApplyToCompanyRequest;
use App\Http\Requests\RenameCompanyRequest;
use App\Http\Requests\SearchCompanyRequest;
use App\Http\Requests\WebAppRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{

    public function create(Request $request){
        $response = [];

        $validatedData = Validator::make($request->all(), [
            'company_name' => 'required|max:255|unique:App\Models\Company,name|min:5|string'
        ], $messages = [
            'company_name.unique' => 'COMPANY_NAME_ALREADY_IN_USE',
        ])->validate();

        if(!$request->user()->company){
            $company = [];
            $company["name"] =  $validatedData["company_name"];
            $company["owner_id"] =  $request->user()->id;
            $createdCompany = $request->user()->company()->create($company);
            $request->user()->company_id = $createdCompany->id;
            $request->user()->save();

            $response["message"] = "SUCCESS";
        }else{
            $response["message"] = "The given data was invalid.";
            $response["errors"]["user"] = ["USER_IS_ALREADY_MEMBER_OF_A_COMPANY"];
            return response($response, 422);
        }

        return response($response, 201);
    }

    public function dashboard(WebAppRequest $request){
        $response = [];

        $response["jobs_delivered_total"] = $request->user()->company->jobs()->where("status", "=", "delivered")->count();

        $last_7_days = \Carbon\Carbon::today()->subDays(7);
        $response["jobs_delivered_7_days"] = $request->user()->company->jobs()->where('created_at', '>=', $last_7_days)->where("status", "=", "delivered")->count();

        $response["latest_5_tours"] = $request->user()->company->jobs()->latest()->limit(5)->with([
            'truck_model',
            'truck_model.truck_manufacturer',
            'city_departure',
            'city_destination',
            'company_departure',
            'company_destination',
            'cargo',
            'cargo.game_item_translation' => function($q) use ($request){
                $q->where('language_code', '=', $request["language_code"])->orWhere('language_code', '=', "en");
            }])->get();

        $response["bank_balance"] = 0;

        $response["employees_total"] = $request->user()->company->users()->count();

        if(strtotime($request->user()->last_client_update) > strtotime("-1 minutes")){
            $response["online_status"] = "ClientOnline";
        }

        $One_minute_ago = \Carbon\Carbon::today()->subMinutes(1);
        $response["employees_online"] = $request->user()->company->users()->where('last_client_update', '>=', $One_minute_ago)->count();

        return $response;
    }

    public function jobs(WebAppRequest $request){
        return $request->user()->company->jobs()->latest()->with([
            'truck_model',
            'truck_model.truck_manufacturer',
            'city_departure',
            'city_destination',
            'company_departure',
            'company_destination',
            'cargo',
            'cargo.game_item_translation' => function($q) use ($request){
                $q->where('language_code', '=', $request["language_code"])->orWhere('language_code', '=', "en");
            }])->paginate(5);
    }

    public function search(SearchCompanyRequest $request){
        $validatedRequest = $request->validated();

        if(!isset($validatedRequest["q"]))
            return Company::latest()->paginate(5);

        return Company::where('name', 'like', '%' . $validatedRequest["q"] . '%')->paginate(5);
    }

    public function apply(ApplyToCompanyRequest $request, $id){
        $company = Company::findorfail($id);
        $validatedRequest = $request->validated();
        $request->user()->job_applications()->create([
            'application_text' => $validatedRequest["application_text"],
            'company_id' => $company->id,
        ]);

        return response("", 201);
    }

    public function applications(WebAppRequest $request){
        $response = $request->user()->company->job_applications()->latest()->with([
            'applicant' => function($q){
                $q->select("id");
            }
        ])->paginate(5);

        $index = 0;
        foreach($response as $application){
            $response[$index]["applicant"]["username"] = User::getUsername($application->applicant->id, $request->user()->latest_vcc_api_token);
            $index++;
        }

        return $response;
    }

    public function application(WebAppRequest $request, $id){
        $response = $request->user()->company->job_applications()->where("id", "=", $id)->with([
            'applicant' => function($q){
                $q->select("id");
            }
        ])->get()->first();

        $response["applicant"]["username"] = User::getUsername($response["applicant"]["id"], $request->user()->latest_vcc_api_token);

        return $response;
    }

    public function application_accept(WebAppRequest $request, $id){
        $application = $request->user()->company->job_applications()->where("id", "=", $id)->get()->first();
        $application->status = "accepted";
        $application->save();
        $application->applicant->company_id = $application->company_id;
        $application->applicant->save();
        return response("", 204);
    }

    public function application_decline(WebAppRequest $request, $id){
        $application = $request->user()->company->job_applications()->where("id", "=", $id)->get()->first();
        $application->status = "declined";
        $application->save();
        return response("", 204);
    }

    public function employees(WebAppRequest $request){
        $response = $request->user()->company->users()->paginate(5);
        $index = 0;
        foreach($response as $user){
            $response[$index]["username"] = User::getUsername($user->id, $request->user()->latest_vcc_api_token);
            $index++;
        }

        return $response;
    }

    public function employee_kick(WebAppRequest $request, $id){
        if($request->user()->id == $id)
            return response("You can't kick yourself.", 409);
        $user = $request->user()->company->users()->where("id", "=", $id)->get()->first();
        $user->company_id = 0;
        $user->save();
    }

    public function delete(WebAppRequest $request){
        $request->user()->company->delete();
        return response("", 204);
    }

    public function leave(WebAppRequest $request){
        if($request->user()->id == $request->user()->company->owner_id)
            return response("You can't leave the company because you are the owner.", 409);

        $request->user()->company_id = 0;
        $request->user()->save();
        return response("", 204);
    }

    public function rename(RenameCompanyRequest $request) {
        $validatedRequest = $request->validated();
        $request->user()->company->name = $validatedRequest["new_company_name"];
        $request->user()->company->save();
        return response("", 204);
    }
}
