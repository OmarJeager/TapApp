<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use Illuminate\Http\Request;

class TstFrequencyFourController extends Controller
{
    public function create(PpmRecord $ppmRecord){
        return view('user.tst.Frequency4.frequency4', compact('ppmRecord'));
    }
}
