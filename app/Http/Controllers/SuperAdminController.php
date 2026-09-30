<?php

namespace App\Http\Controllers;

use App\Models\PpmChecklist;
use App\Models\PpmRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    private const FILTERS = ['job_id', 'week_due', 'type', 'asset_id', 'frequency', 'completed_by', 'state'];

    private function filteredQuery(Request $request)
    {
        $q = PpmRecord::query();

        if ($v = trim((string) $request->job_id)) {
            $q->where('job_id', 'like', "%{$v}%");
        }
        if ($v = trim((string) $request->week_due)) {
            $q->where('week_due', 'like', "%{$v}%");
        }
        if ($v = trim((string) $request->type)) {        // PNL / TRQ / TST
            $q->where('asset_id', 'like', "{$v}%");
        }
        if ($v = trim((string) $request->asset_id)) {    // starts with
            $q->where('asset_id', 'like', "{$v}%");
        }
        if ($v = trim((string) $request->frequency)) {
            $q->where('frequency', $v);
        }
        if ($v = trim((string) $request->completed_by)) {
            $q->whereHas('checklist.completedBy', function ($u) use ($v) {
            $u->where('name', 'like', "%{$v}%")
             ->orWhere('matricule', 'like', "%{$v}%");
    });
}
        switch ($request->state) {
            case 'verified':
                $q->whereHas('checklist', fn ($c) => $c->where('status_admin', 'verified'));
                break;
            case 'not_verified':
                $q->whereHas('checklist', fn ($c) => $c->where('status_admin', '!=', 'verified'));
                break;
            case 'none':
                $q->whereDoesntHave('checklist');
                break;
        }

        return $q;
    }

    public function index(Request $request)
    {
        $ppm_records = $this->filteredQuery($request)
            ->with('checklist.completedBy')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        // Live search: return JSON with rendered rows + pagination
        if ($request->ajax()) {
            return response()->json([
                'total' => $ppm_records->total(),
                'rows'  => view('superadmin.partials.rows', compact('ppm_records'))->render(),
                'links' => (string) $ppm_records->links(),
            ]);
        }

        $frequencies = PpmRecord::whereNotNull('frequency')
            ->distinct()->orderBy('frequency')->pluck('frequency');

        return view('superadmin.index', compact('ppm_records', 'frequencies'));
    }

    public function show(PpmRecord $ppmRecord)
    {
        $ppmRecord->load([
            'checklist.completedBy',
            'checklist.verifiedBy',
            'checklist.verifiedByQuality',
            'checklist.answers.question',
        ]);

        $answers = optional($ppmRecord->checklist)->answers
            ?->sortBy(fn ($a) => $a->question->order ?? 0);

        return view('superadmin.show', [
            'record'  => $ppmRecord,
            'answers' => $answers ?? collect(),
        ]);
    }

    public function toggleStatus(PpmChecklist $checklist)
    {
        if ($checklist->status_admin === 'verified') {
            $checklist->update([
                'status_admin'          => 'not_verified',
                'verified_by_matricule' => null,
                'verified_at'           => null,
            ]);
        } else {
            $checklist->update([
                'status_admin'          => 'verified',
                'verified_by_matricule' => Auth::user()->matricule,
                'verified_at'           => now()->toDateString(),
            ]);
        }

        return back()->with('success', 'Status updated.');
    }

    public function bulkStatus(Request $request)
    {
        $request->validate(['status' => 'required|in:verified,not_verified']);

        $ids = $this->filteredQuery($request)->select('id');
        $checklists = PpmChecklist::whereIn('ppm_records_id', $ids);

        $count = $request->status === 'verified'
            ? $checklists->update([
                'status_admin'          => 'verified',
                'verified_by_matricule' => Auth::user()->matricule,
                'verified_at'           => now()->toDateString(),
            ])
            : $checklists->update([
                'status_admin'          => 'not_verified',
                'verified_by_matricule' => null,
                'verified_at'           => null,
            ]);

        return redirect()
            ->route('superadmin.index', $request->only(self::FILTERS))
            ->with('success', "{$count} checklist(s) updated.");
    }
}
