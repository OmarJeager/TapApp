<?php

namespace App\Http\Controllers;
use App\Models\PpmRecord;
use Illuminate\Http\Request;

class TstFreauencyFourHvController extends Controller
{
    public function frequency4Hv(PpmRecord $ppmRecord)
    {
        return view('user.tst.Frequency4hv.Frequency4hv',compact('ppmRecord'));
    }
}
