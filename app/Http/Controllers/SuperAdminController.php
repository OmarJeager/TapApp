<?php

namespace App\Http\Controllers;

use App\Models\PpmChecklist;
use App\Models\PpmRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
       return view('superadmin.dashboard', [
        'pendingCount' => $this->pendingChecklists()->count(),
    ]);
    }

    private const FILTERS = ['job_id', 'week_due', 'type', 'asset_id', 'frequency', 'completed_by', 'year', 'state'];

    private function filteredQuery(Request $request)
    {
        $q = PpmRecord::query();

        // Filter by year (defaults to current year) - week_due format: 202636
        $year = trim((string) $request->input('year', now()->year));
        if ($year !== '') {
            $q->where('week_due', 'like', "{$year}%");
        }

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
            'checklist.answers.question',   // original question: only used as a fallback for old data
            'checklist.questions',          // snapshot questions saved with this checklist
        ]);

        $checklist = $ppmRecord->checklist;
        $answers   = collect();

        if ($checklist) {
            // Snapshots of this checklist, keyed by the original question id
            $snapshots = $checklist->questions->keyBy('checklist_question_id');

            $answers = $checklist->answers
                ->map(function ($answer) use ($snapshots) {
                    // Use the snapshot when it exists, so hidden / edited / removed
                    // questions never change an old checklist.
                    // Snapshot has the same fields the view reads:
                    // question_text, type, frequency, variant, order.
                    if ($snap = $snapshots->get($answer->checklist_question_id)) {
                        $answer->setRelation('question', $snap);
                    }

                    return $answer;
                })
                ->sortBy(fn ($a) => $a->question->order ?? 0)
                ->values();
        }

        return view('superadmin.show', [
            'record'  => $ppmRecord,
            'answers' => $answers,
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
    /** Checklists completed by a user but not yet verified by admin. */
private function pendingChecklists()
{
    return PpmChecklist::query()
        ->whereNotNull('completed_at')
        ->where(function ($q) {
            $q->whereNull('status_admin')
              ->orWhere('status_admin', '!=', 'verified');
        })
        ->orderByDesc('completed_at')
        ->orderByDesc('id');
}

/** JSON: count + rendered cards (used by the bell). */
public function notifications()
{
    $checklists = $this->pendingChecklists()
        ->with([
            'ppmRecord',
            'completedBy',
            'questions',
            'answers.question',
        ])
        ->limit(50)
        ->get();

    // Use the snapshot question text when it exists (same logic as show())
    $checklists->each(function ($checklist) {
        $snapshots = $checklist->questions->keyBy('checklist_question_id');

        $answers = $checklist->answers
            ->map(function ($answer) use ($snapshots) {
                if ($snap = $snapshots->get($answer->checklist_question_id)) {
                    $answer->setRelation('question', $snap);
                }
                return $answer;
            })
            ->sortBy(fn ($a) => $a->question->order ?? 0)
            ->values();

        $checklist->setRelation('answers', $answers);
    });

    return response()->json([
        'count' => $this->pendingChecklists()->count(),
        'html'  => view('superadmin.partials.notification-cards', compact('checklists'))->render(),
    ]);
}

/** Mark one checklist as verified by the admin (status_admin = verified). */
public function verifyChecklist(PpmChecklist $checklist)
{
    $checklist->update([
        'status_admin'          => 'verified',
        'verified_by_matricule' => Auth::user()->matricule,
        'verified_at'           => now()->toDateString(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Checklist verified.',
        'count'   => $this->pendingChecklists()->count(),
    ]);
}

}
