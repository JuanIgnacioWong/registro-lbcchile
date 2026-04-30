<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicSubmissionRequest;
use App\Models\Club;
use App\Models\Division;
use App\Models\PlatformSetting;
use App\Models\Season;
use App\Services\AuditLogger;
use App\Services\SubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicRegistrationController extends Controller
{
    public function __construct(
        private readonly SubmissionService $submissionService,
        private readonly AuditLogger $auditLogger,
    ) {
    }

    public function create(): View
    {
        $settings = PlatformSetting::query()->pluck('value', 'key')->all();

        return view('public.inscription', [
            'seasons' => Season::query()->where('is_active', true)->orderByDesc('year')->get(),
            'settings' => $settings,
        ]);
    }

    public function store(StorePublicSubmissionRequest $request): RedirectResponse
    {
        try {
            [$submission, $version] = $this->submissionService->submitPublic(
                $request->integer('season_id'),
                $request->integer('division_id'),
                $request->integer('club_id'),
                trim(strip_tags((string) $request->string('responsible_name'))),
                trim(strip_tags((string) $request->string('phone'))),
                strtolower(trim((string) $request->string('email'))),
                $request->file('club_logo'),
                $request->file('payment_receipt'),
                $request->file('players_roster'),
                trim(strip_tags((string) $request->string('observations'))),
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $this->auditLogger->log(
            null,
            'public_submission_created',
            'submission_version',
            $version->id,
            'Nuevo envio publico de antecedentes.',
            ['submission_id' => $submission->id]
        );

        return back()->with('success', 'Antecedentes enviados correctamente.');
    }

    public function divisionsBySeason(Season $season): JsonResponse
    {
        return response()->json([
            'data' => Division::query()
                ->where('season_id', $season->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
        ]);
    }

    public function clubsBySeasonDivision(Season $season, int $division): JsonResponse
    {
        $divisionModel = Division::query()
            ->whereKey($division)
            ->where('season_id', $season->id)
            ->firstOrFail();

        return response()->json([
            'data' => Club::query()
                ->where('season_id', $season->id)
                ->where('division_id', $divisionModel->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
        ]);
    }

    public function downloadRosterTemplate(): StreamedResponse
    {
        $template = PlatformSetting::value('roster_template');

        abort_unless($template && Storage::disk('local')->exists($template), 404);

        return Storage::disk('local')->download($template);
    }
}
