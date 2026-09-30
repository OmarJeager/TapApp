<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Categories are matched by asset_id prefix.
     * Adjust this list if you add more sites/prefixes later.
     */
    protected array $categories = ['pnl', 'tst', 'khm'];

    /**
     * Step 1: show the selection page (category -> week -> [frequency for tst]).
     */
    public function index()
    {
        return view('user.print.select', [
            'categories' => $this->categories,
        ]);
    }

    /**
     * AJAX: return distinct weeks available for a given category.
     */
    public function weeks(Request $request)
    {
        $request->validate([
            'category' => 'required|in:' . implode(',', $this->categories),
        ]);

        $weeks = PpmRecord::where('asset_id', 'like', $request->category . '%')
            ->whereNotNull('week_due')
            ->distinct()
            ->orderBy('week_due')
            ->pluck('week_due');

        return response()->json($weeks);
    }

    /**
     * AJAX: return distinct frequencies for a category (used for TST).
     */
    public function frequencies(Request $request)
    {
        $request->validate([
            'category' => 'required|in:' . implode(',', $this->categories),
            'week'     => 'required',
        ]);

        $frequencies = PpmRecord::where('asset_id', 'like', $request->category . '%')
            ->where('week_due', $request->week)
            ->whereNotNull('frequency')
            ->distinct()
            ->orderBy('frequency')
            ->pluck('frequency');

        return response()->json($frequencies);
    }

    /**
     * Step 2: build the printable ticket sheet for every asset matching
     * the chosen category + week (+ frequency, required only for TST).
     */
    public function print(Request $request)
    {
        $request->validate([
            'category'  => 'required|in:' . implode(',', $this->categories),
            'week'      => 'required',
            'frequency' => 'nullable|string',
        ]);

        $query = PpmRecord::where('asset_id', 'like', $request->category . '%')
            ->where('week_due', $request->week);

        // TST tickets are also filtered/split by frequency
        if ($request->category === 'tst' && $request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        $records = $query->orderBy('asset_id')->with('ppmChecklists')->get();

        return view('user.print.print', [
            'records'  => $records,
            'category' => strtoupper($request->category),
            'week'     => $request->week,
        ]);
    }
}
