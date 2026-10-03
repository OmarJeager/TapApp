<?php

namespace App\Http\Controllers;

use App\Models\ChecklistQuestion;
use App\Models\PpmRecord;
use Illuminate\Http\Request;

class TstFreauencyFourHvController extends Controller
{
    public function frequency4Hv(PpmRecord $ppmRecord)
    {
         $questions = ChecklistQuestion::where('type', 'TST')
        ->where('frequency', 4)
        ->where('variant', 'HV')
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
        return view('user.tst.Frequency4hv.Frequency4hv',compact('ppmRecord','questions'));
    }
}
