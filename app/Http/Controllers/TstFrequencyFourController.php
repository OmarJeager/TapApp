<?php

namespace App\Http\Controllers;

use App\Models\ChecklistQuestion;
use App\Models\PpmRecord;
use Illuminate\Http\Request;

class TstFrequencyFourController extends Controller
{
    public function create(PpmRecord $ppmRecord){
        $questions = ChecklistQuestion::where('type', 'TST')
        ->where('frequency', 4)
        ->where('variant', null)
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
        return view('user.tst.Frequency4.frequency4', compact('ppmRecord', 'questions'));
    }
}
