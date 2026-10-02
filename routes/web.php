<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PnlChecklistController;
use App\Http\Controllers\PpmRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TstFrequencyFourController;
use App\Http\Controllers\TstFrequencyOneController;
use App\Http\Controllers\UserController;
use App\Models\PpmRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Shared routes for ALL authenticated users (any role)
Route::middleware('auth')->group(function () {
    Route::get('/role-error', function () {
        return view('errors.role');
    })->name('role.error');

    // Profile routes — accessible by all roles
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile picture + signature — accessible by all roles
    Route::post('/profile/upload-picture', [UserController::class, 'uploadPicture'])
        ->name('profile.upload-picture');
    Route::post('/profile/signature', [SignatureController::class, 'update'])
        ->name('profile.signature.update');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');
    Route::get('/superadmin/verfied', [SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::get('/ppm/{ppmRecord}', [SuperAdminController::class, 'show'])->name('superadmin.show');
    Route::patch('/checklist/{checklist}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('superadmin.toggle');
    Route::post('/bulk-status', [SuperAdminController::class, 'bulkStatus'])->name('superadmin.bulk');
});

Route::middleware(['auth', 'role:quality'])->group(function () {
    Route::get('/qualitydashboard', [QualityController::class, 'dashboard'])->name('quality.dashboard');
    Route::get('/quality/index', [QualityController::class, 'index'])->name('quality.index');
    Route::get('/ppm/quality/{ppmRecord}', [QualityController::class, 'show'])->name('quality.show');
    Route::patch('/checklistquality/{checklist}/toggle-status', [QualityController::class, 'toggleStatus'])->name('quality.toggle');
    Route::post('/bulk-statusquality', [QualityController::class, 'bulkStatus'])->name('quality.bulk');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('/admin/ppm-records/export/{format}', [AdminController::class, 'export'])
    ->name('ppm-records.export');
    Route::get('/adminhomepage', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/ppm-records', [AdminController::class, 'index'])->name('ppm-records.index');
    Route::post('/admin/ppm-records/import', [AdminController::class, 'import'])
        ->name('ppm-records.import');
    Route::get('/admin/ppm-records/search', function (Request $request) {
        $records = PpmRecord::where('job_id', 'like', '%' . $request->job_id . '%')
            ->limit(10)
            ->get();

        return response()->json($records->map(function ($record) {
            return [
                'id' => $record->id,
                'job_id' => $record->job_id,
                'asset_id' => $record->asset_id,
                'ppm_id' => $record->ppm_id,
                'url' => route('ppm-records.show', $record->id),
            ];
        }));
    })->name('ppm-records.search');

    Route::get('/admin/ppm-records/{ppmRecord}', [AdminController::class, 'show'])
        ->name('ppm-records.show');
    Route::get('/adminshowdetails', [AdminController::class, 'showdetails'])
        ->name('admin.showdetails');
          Route::get('/ppm-recordsshowdeatils/{ppmRecord}', [AdminController::class, 'show'])->name('ppm-records.show');
});

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/ppm-records', [PpmRecordController::class, 'index'])->name('user.index');
    Route::get('/ppm-records/{id}', [PpmRecordController::class, 'show'])->name('user.show');

    Route::post('/ppm-records', [PpmRecordController::class, 'store'])
        ->name('ppm-records.store');
    Route::get('/ppm-records/{ppmRecord}/pnl-form', [PnlChecklistController::class, 'createPnlForm'])
        ->name('ppm-checklists.pnl.create');
    Route::post('/ppm-checklists/pnl', [PnlChecklistController::class, 'storePnl'])
        ->name('ppm-checklists.pnl.store');
    Route::get('/ppm-records/{ppmRecord}/form', [PpmRecordController::class, 'form'])
        ->name('ppm-records.form');
    Route::get('/ppm-records/{ppmRecord}/tstf1-form', [TstFrequencyOneController::class, 'create'])
        ->name('ppm-checklists.tst.frequency1');
    Route::get('/ppm-records/{ppmRecord}/tstf4-form', [TstFrequencyFourController::class, 'create'])
        ->name('ppm-checklists.tst.frequency4');
    Route::get('/tickets', [TicketController::class, 'index'])
        ->name('tickets.index');
    Route::get('/tickets/weeks', [TicketController::class, 'weeks'])
        ->name('tickets.weeks');
    Route::get('/tickets/frequencies', [TicketController::class, 'frequencies'])
        ->name('tickets.frequencies');
    Route::get('/tickets/print', [TicketController::class, 'print'])
        ->name('tickets.print');
});

require __DIR__.'/auth.php';
