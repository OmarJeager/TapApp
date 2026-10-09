<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PnlChecklistController;
use App\Http\Controllers\PpmRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TstFreauencyFourHvController;
use App\Http\Controllers\TstFrequencyFourController;
use App\Http\Controllers\TstFrequencyOneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CeoController;
use App\Http\Controllers\ChecklistQuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditRequestController;
use App\Http\Controllers\AssetScanController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Schedule;
use App\Models\PpmRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->get('/dashboardall', [DashboardController::class, 'index'])->name('dashboardall');
Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Shared routes for ALL authenticated users (any role)
Route::middleware('auth')->group(function () {
    Route::get('/role-error', function () {
        return view('errors.role');
    })->name('role.error');
    Route::get('/dashboardd', [DashboardController::class, 'index'])->name('dashboard');
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
Route::middleware(['auth', 'role:ceo'])->group(function () {
    Route::get('/ceodashboard', [CeoController::class, 'dashboard'])->name('ceo.dashboard');

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [
            CeoController::class,
            'index'
        ])->name('ceo.users.index');


        Route::get('/users/create', [
            CeoController::class,
            'create'
        ])->name('ceo.users.create');


        Route::post('/users', [
            CeoController::class,
            'store'
        ])->name('ceo.users.store');


        Route::get('/users/{user}/edit', [
            CeoController::class,
            'edit'
        ])->name('ceo.users.edit');


        Route::put('/users/{user}', [
            CeoController::class,
            'update'
        ])->name('ceo.users.update');


        Route::delete('/users/{user}', [
            CeoController::class,
            'destroy'
        ])->name('ceo.users.destroy');


        /*
        |--------------------------------------------------------------------------
        | Delete profile picture
        |--------------------------------------------------------------------------
        */

        Route::delete('/users/{user}/picture', [
            CeoController::class,
            'deletePicture'
        ])->name('ceo.users.picture.destroy');
        
        Route::patch('/ceo/users/{user}/toggle-status',
             [CeoController::class, 'toggleAccountStatus']
        )->name('ceo.users.toggle-status');
});
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');
    Route::get('/superadmin/verfied', [SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::get('/ppm/{ppmRecord}', [SuperAdminController::class, 'show'])->name('superadmin.show');
    Route::patch('/checklist/{checklist}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('superadmin.toggle');
    Route::post('/bulk-status', [SuperAdminController::class, 'bulkStatus'])->name('superadmin.bulk');
    Route::get('/superadmin/notifications', [SuperAdminController::class, 'notifications'])
    ->name('superadmin.notifications');
    Route::post('/superadmin/notifications/{checklist}/verify', [SuperAdminController::class, 'verifyChecklist'])
    ->name('superadmin.notifications.verify');
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
    Schedule::command('ppm:import-folder')->everyMinute()->withoutOverlapping();
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
        Route::get('/ppm-recordsshowdeatils/{ppmRecord}', [AdminController::class, 'show'])->name('ppm-records.show');
        Route::get('/checklist-questions',                    [ChecklistQuestionController::class, 'showQuestions'])->name('admin.checklist-questions.index');
        Route::post('/checklist-questions',                   [ChecklistQuestionController::class, 'store'])->name('admin.checklist-questions.store');
        Route::put('/checklist-questions/{question}',          [ChecklistQuestionController::class, 'update'])->name('admin.checklist-questions.update');
        Route::patch('/checklist-questions/{question}/toggle', [ChecklistQuestionController::class, 'toggle'])->name('admin.checklist-questions.toggle');
        Route::delete('/checklist-questions/{question}',       [ChecklistQuestionController::class, 'destroy'])->name('admin.checklist-questions.destroy');
        Route::get('/admin/ppm-weeks', [AdminController::class, 'weeks'])
        ->name('admin.ppm-weeks');

        Route::patch('/admin/ppm-weeks/{week}/toggle',[AdminController::class, 'toggleWeekPublication'])
        ->name('admin.ppm-weeks.toggle');
         // Publish draft week
        Route::patch('/admin/ppm-weeks/{week}/publish',
        [AdminController::class, 'publishWeek'])
        ->name('admin.ppm-weeks.publish');


         // Push already published week again
        Route::patch('/admin/ppm-weeks/{week}/push',[AdminController::class, 'pushWeek'])
        ->name('admin.ppm-weeks.push');


        // Hide week
         Route::patch('/admin/ppm-weeks/{week}/hide',[AdminController::class, 'hideWeek'])
         ->name('admin.ppm-weeks.hide');


        // Complete week
         Route::patch('/admin/ppm-weeks/{week}/complete',[AdminController::class, 'completeWeek'])
         ->name('admin.ppm-weeks.complete');


        // Archive week
         Route::patch('/admin/ppm-weeks/{week}/archive',[AdminController::class, 'archiveWeek'])
         ->name('admin.ppm-weeks.archive');


        // Reopen week
        Route::patch('/admin/ppm-weeks/{week}/reopen',[AdminController::class, 'reopenWeek'])
        ->name('admin.ppm-weeks.reopen');
    Route::get('/admin/edit-requests', [EditRequestController::class, 'index'])->name('admin.edit-requests.index');
Route::post('/admin/edit-requests/{editRequest}/approve', [EditRequestController::class, 'approve'])->name('admin.edit-requests.approve');
Route::post('/admin/edit-requests/{editRequest}/reject', [EditRequestController::class, 'reject'])->name('admin.edit-requests.reject');
});

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/ppm-records', [PpmRecordController::class, 'index'])->name('user.index');
    //Route::get('/ppm-records/{id}', [PpmRecordController::class, 'show'])->name('user.show');

    Route::post('/ppm-records', [PpmRecordController::class, 'store'])
        ->name('ppm-records.store');
    Route::get('/ppm-records/{ppmRecord}/pnl-form', [PnlChecklistController::class, 'createPnlForm'])
        ->name('ppm-checklists.pnl.create');
    Route::post('/ppm-checklists/pnl', [PnlChecklistController::class, 'storePnl'])
        ->name('ppm-checklists.pnl.store');
    Route::post('/ppm-records/scan', [PnlChecklistController::class, 'scan'])
        ->name('ppm-records.scan');
    Route::get('/ppm-records/{ppmRecord}/form', [PpmRecordController::class, 'form'])
        ->name('ppm-records.form');
    Route::get('/ppm-records/{ppmRecord}/tstf1-form', [TstFrequencyOneController::class, 'create'])
        ->name('ppm-checklists.tst.frequency1');
        Route::post('/tst-frequency-1', [TstFrequencyOneController::class, 'store'])
    ->name('ppm-checklists.tst1.store');

    Route::post('/ppm-records/{ppmRecord}/tst-frequency-1/edit-request', [TstFrequencyOneController::class, 'requestEdit'])
    ->name('ppm-checklists.tst1.edit-request');
    Route::get('/ppm-records/{ppmRecord}/tstf4-form', [TstFrequencyFourController::class, 'create'])
        ->name('ppm-checklists.tst.frequency4');
        Route::post('/tst-frequency-4', [TstFrequencyFourController::class, 'store'])
    ->name('ppm-checklists.tst4.store');

    Route::post('/ppm-records/{ppmRecord}/tst-frequency-4/edit-request', [TstFrequencyFourController::class, 'requestEdit'])
    ->name('ppm-checklists.tst4.edit-request');
    Route::get(
    '/ppm-records/{ppmRecord}/tst-frequency4-hv',[TstFreauencyFourHvController::class, 'frequency4Hv'])
        ->name('ppm-checklists.tst.frequency4hv');
    Route::post('/ppm-records/{ppmRecord}/pnl-edit-request', [PnlChecklistController::class, 'requestEdit'])
    ->name('ppm-checklists.pnl.edit-request');
    Route::get('/scanasset', [AssetScanController::class, 'index'])
    ->name('scan.index');
    //Route::get('/scan', [AssetScanController::class, 'index'])->name('scan.index');
    Route::post('/scan/lookup', [AssetScanController::class, 'lookup'])->name('asset-scan.lookup');
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
