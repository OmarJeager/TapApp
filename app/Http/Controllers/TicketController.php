<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    protected array $categories = ['pnl', 'tst', 'khm'];

    public function index()
    {
        return view('user.print.select', [
            'categories' => $this->categories,
        ]);
    }

    public function weeks(Request $request)
{
    $request->validate([
        'category' => 'required|in:' . implode(',', $this->categories),
        'year'     => 'nullable|digits:4',
        'week'     => 'nullable|digits_between:1,2',
    ]);

    $query = PpmRecord::where('asset_id', 'like', $request->category . '%')
        ->whereNotNull('week_due')
        ->whereIn('week_due', $this->publishedWeeks());

    // Search by year: 2026 -> 2026xx
    if ($request->filled('year')) {
        $query->where('week_due', 'like', $request->year . '%');
    }

    // Search by week number: 40 -> xxxx40
    if ($request->filled('week')) {
        $query->where('week_due', 'like', '%' . str_pad($request->week, 2, '0', STR_PAD_LEFT));
    }

    return response()->json(
        $query->distinct()->orderBy('week_due')->pluck('week_due')
    );
}

public function frequencies(Request $request)
{
    $request->validate([
        'category' => 'required|in:' . implode(',', $this->categories),
        'week'     => 'required',
    ]);

    $frequencies = PpmRecord::where('asset_id', 'like', $request->category . '%')
        ->where('week_due', $request->week)
        ->whereIn('week_due', $this->publishedWeeks())
        ->whereNotNull('frequency')
        ->distinct()
        ->orderBy('frequency')
        ->pluck('frequency');

    return response()->json($frequencies);
}

    /**
     * AJAX: for TST + frequency 4, tell the page which variants exist
     * ("hv" = asset_description contains "hv", "normal" = everything else).
     */
    public function variants(Request $request)
    {
        $request->validate([
            'category'  => 'required|in:tst',
            'week'      => 'required',
            'frequency' => 'required',
        ]);

        $base = fn () => PpmRecord::where('asset_id', 'like', 'tst%')
            ->where('week_due', $request->week)
            ->where('frequency', $request->frequency);

        $variants = [];

        if ($this->applyVariant($base(), 'normal')->exists()) {
            $variants[] = 'normal';
        }

        if ($this->applyVariant($base(), 'hv')->exists()) {
            $variants[] = 'hv';
        }

        return response()->json($variants);
    }

    public function print(Request $request)
    {
        $request->validate([
            'category'  => 'required|in:' . implode(',', $this->categories),
            'week'      => 'required',
            'frequency' => 'nullable|string',
            'variant'   => 'nullable|in:normal,hv',
        ]);

        $query = PpmRecord::where('asset_id', 'like', $request->category . '%')
           ->where('week_due', $request->week)
           ->whereIn('week_due', $this->publishedWeeks());

        if ($request->category === 'tst' && $request->filled('frequency')) {
            $query->where('frequency', $request->frequency);

            // Frequency 4: HV assets are printed on their own
            if ((string) $request->frequency === '4' && $request->filled('variant')) {
                $this->applyVariant($query, $request->variant);
            }
        }

        $records = $query->orderBy('asset_id')->with('ppmChecklists')->get();

        return view('user.print.print', [
            'records'  => $records,
            'category' => strtoupper($request->category)
                . ($request->variant === 'hv' ? ' (HV)' : ''),
            'week'     => $request->week,
        ]);
    }

    /**
     * hv     => asset_description contains "hv"
     * normal => asset_description is NULL or does not contain "hv"
     */
    protected function applyVariant($query, string $variant)
    {
        if ($variant === 'hv') {
            return $query->where('asset_description', 'like', '%hv%');
        }

        return $query->where(function ($q) {
            $q->whereNull('asset_description')
              ->orWhere('asset_description', 'not like', '%hv%');
        });
    }
    /** Only week_due values marked as published. */
protected function publishedWeeks()
{
    return DB::table('ppm_week_controls')
        ->where('is_published', 1)
        ->select('week_due');
}
}
