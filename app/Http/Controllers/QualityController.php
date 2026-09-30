<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QualityController extends Controller
{
    public function index(){
        return view('quality.dashboard');
    }
}
