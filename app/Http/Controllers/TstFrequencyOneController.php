<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use Illuminate\Http\Request;

class TstFrequencyOneController extends Controller
{
    public function create(PpmRecord $ppmRecord){
        return view('user.tst.Frequency1.frequency1', compact('ppmRecord'));
    }
}
