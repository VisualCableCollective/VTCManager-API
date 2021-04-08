<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class JobApplicationsController extends Controller
{
    public $current_route;
    public function __construct()
    {
        $this->current_route = Route::currentRouteName();
    }

    public function index($company_id){
        //$company = \App\Models\Company::findorfail($company_id);
        return view("company.applications.index", ["current_route" => $this->current_route/*,"company" => $company*/]);
    }

    public function show($company_id, $application_id){
        //$company = \App\Models\Company::findorfail($company_id);
        //$application = \App\Models\JobApplication::findorfail($application_id);
        return view("company.applications.show", ["current_route" => $this->current_route/*,"company" => $company, "application" => $application*/]);
    }
}
