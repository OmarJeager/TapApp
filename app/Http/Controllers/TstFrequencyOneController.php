<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use App\Models\ChecklistQuestion;
use Illuminate\Http\Request;

class TstFrequencyOneController extends Controller
{
    public function create(PpmRecord $ppmRecord){
        $questions = ChecklistQuestion::where('type', 'TST')
        ->where('frequency', 1)
        ->where('variant', null)
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
        return view('user.tst.Frequency1.frequency1', compact('ppmRecord', 'questions'));
    }
}
