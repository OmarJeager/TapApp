<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index(){
        $ppm_records=PpmRecord::all();
        return view('superadmin.index',compact('ppm_records'));
    }

}
