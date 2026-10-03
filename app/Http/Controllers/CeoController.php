<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CeoController extends Controller
{
    //
    public function index()
    {
        return view('ceo.index');
    }
}
