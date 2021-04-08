<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class EmployeesController extends Controller
{
    public $current_route;
    public function __construct()
    {
        $this->current_route = Route::currentRouteName();
    }

    public function index($company_id){
        //$company = \App\Models\Company::findorfail($id);
        return view("company.employees", ["current_route" => $this->current_route/*,"company" => $company*/]);
    }
}
