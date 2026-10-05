<?php

namespace App\Http\Controllers;

use App\Models\PpmChecklistEditRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditRequestController extends Controller
{
    //
    public function index()
    {
        $requests = PpmChecklistEditRequest::with('checklist.ppmRecord')
            ->where('status', 'pending')->latest()->get();

        return view('admin.edit-requests.index', compact('requests'));
    }

    public function approve(PpmChecklistEditRequest $editRequest)
    {
        $editRequest->update([
            'status'               => 'approved',
            'decided_by_matricule' => Auth::user()->matricule,
            'decided_at'           => now(),
        ]);
        return back()->with('status', 'Request approved.');
    }

    public function reject(Request $request, PpmChecklistEditRequest $editRequest)
    {
        $data = $request->validate(['admin_note' => ['required', 'string', 'max:1000']]);

        $editRequest->update([
            'status'               => 'rejected',
            'admin_note'           => $data['admin_note'],
            'decided_by_matricule' => Auth::user()->matricule,
            'decided_at'           => now(),
        ]);
        return back()->with('status', 'Request rejected.');
    }
}
