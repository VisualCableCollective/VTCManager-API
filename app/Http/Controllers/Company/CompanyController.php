<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class CompanyController extends Controller
{
    public $current_route;
    public function __construct()
    {
        $this->current_route = Route::currentRouteName();
    }

    public function show($id){
        //$company = \App\Models\Company::findorfail($id);
        return view("company.show", ["current_route" => $this->current_route/*,"company" => $company*/]);
    }
}
