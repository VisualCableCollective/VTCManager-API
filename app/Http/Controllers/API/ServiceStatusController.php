<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceStatusController extends Controller
{
    public static function index(){
        $response = [];
        $WebAppStatus = [];
        $WebAppStatus["operational"] = true;
        $response["WebApp"] = $WebAppStatus;
        $DesktopClientStatus = [];
        $DesktopClientStatus["operational"] = true;
        $response["DesktopClient"] = $DesktopClientStatus;
        return $response;
    }
}
