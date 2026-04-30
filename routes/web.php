<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\ClubController;
use App\Http\Controllers\Admin\CorrectionLinkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\FileDownloadController;
use App\Http\Controllers\Admin\PlatformSettingController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CorrectionSubmissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicRegistrationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/inscripcion');

Route::get('/inscripcion', [PublicRegistrationController::class, 'create'])->name('public.inscription.create');
Route::post('/inscripcion', [PublicRegistrationController::class, 'store'])
    ->middleware('throttle:public-submission')
    ->name('public.inscription.store');

Route::get('/inscripcion/options/{season:id}/divisiones', [PublicRegistrationController::class, 'divisionsBySeason'])
    ->name('public.inscription.divisions');

Route::get('/inscripcion/options/{season:id}/{division:id}/clubes', [PublicRegistrationController::class, 'clubsBySeasonDivision'])
    ->name('public.inscription.clubs');


Route::get('/inscripcion/plantilla-nomina', [PublicRegistrationController::class, 'downloadRosterTemplate'])
    ->name('public.inscription.template');

Route::get('/correcciones/{year}/{division}/{club}/{token}', [CorrectionSubmissionController::class, 'create'])
    ->middleware('throttle:corrections-submission')
    ->name('public.corrections.create');
Route::post('/correcciones/{year}/{division}/{club}/{token}', [CorrectionSubmissionController::class, 'store'])
    ->middleware('throttle:corrections-submission')
    ->name('public.corrections.store');

Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('seasons', SeasonController::class)->except('show');
        Route::resource('divisions', DivisionController::class)->except('show');
        Route::resource('clubs', ClubController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');

        Route::get('/antecedentes', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/antecedentes/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/antecedentes/{submission}/payment-status', [SubmissionController::class, 'updatePaymentStatus'])
            ->name('submissions.payment-status');
        Route::post('/antecedentes/{submission}/extra-slot', [SubmissionController::class, 'enableExtraSubmission'])
            ->name('submissions.extra-slot');
        Route::post('/antecedentes/versiones/{version}/accept', [SubmissionController::class, 'acceptVersion'])
            ->name('submissions.versions.accept');
        Route::post('/antecedentes/versiones/{version}/reject', [SubmissionController::class, 'rejectVersion'])
            ->name('submissions.versions.reject');
        Route::delete('/antecedentes/versiones/{version}', [SubmissionController::class, 'destroyVersion'])
            ->name('submissions.versions.destroy');

        Route::get('/descargas/version/{version}/{fileType}', [FileDownloadController::class, 'downloadVersionFile'])
            ->name('downloads.version');
        Route::get('/descargas/submission/{submission}/all', [FileDownloadController::class, 'downloadAll'])
            ->name('downloads.submission-all');

        Route::get('/correcciones', [CorrectionLinkController::class, 'index'])->name('corrections.index');
        Route::post('/correcciones', [CorrectionLinkController::class, 'store'])->name('corrections.store');
        Route::post('/correcciones/{correctionLink}/toggle', [CorrectionLinkController::class, 'toggle'])->name('corrections.toggle');
        Route::post('/correcciones/{correctionLink}/regenerate', [CorrectionLinkController::class, 'regenerate'])->name('corrections.regenerate');

        Route::get('/configuracion', [PlatformSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/configuracion', [PlatformSettingController::class, 'update'])->name('settings.update');

        Route::get('/pagos', [SubmissionController::class, 'index'])->name('payments.index');
        Route::get('/historial', [AuditLogController::class, 'index'])->name('history.index');
    });

require __DIR__.'/auth.php';
