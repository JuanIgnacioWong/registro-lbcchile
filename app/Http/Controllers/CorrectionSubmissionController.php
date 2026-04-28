<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorrectionSubmissionRequest;
use App\Models\CorrectionLink;
use App\Models\Submission;
use App\Services\AuditLogger;
use App\Services\SubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CorrectionSubmissionController extends Controller
{
    public function __construct(
        private readonly SubmissionService $submissionService,
        private readonly AuditLogger $auditLogger,
    ) {
    }

    public function create(string $year, string $division, string $club, string $token): View
    {
        $link = $this->resolveLink($year, $division, $club, $token);

        $submission = Submission::query()
            ->where('season_id', $link->season_id)
            ->where('division_id', $link->division_id)
            ->where('club_id', $link->club_id)
            ->withCount('versions')
            ->first();

        abort_unless($submission, 404, 'No existe envio inicial para este club.');
        abort_unless($submission->versions_count < $submission->max_allowed_submissions, 403, 'No hay cupos de correccion disponibles.');

        return view('public.correction', [
            'link' => $link,
            'submission' => $submission,
        ]);
    }

    public function store(StoreCorrectionSubmissionRequest $request, string $year, string $division, string $club, string $token): RedirectResponse
    {
        $link = $this->resolveLink($year, $division, $club, $token);

        $submission = Submission::query()
            ->where('season_id', $link->season_id)
            ->where('division_id', $link->division_id)
            ->where('club_id', $link->club_id)
            ->firstOrFail();

        try {
            $version = $this->submissionService->createVersion(
                $submission,
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
            'correction_submission_created',
            'submission_version',
            $version->id,
            'Correccion recibida desde enlace seguro.',
            ['submission_id' => $submission->id, 'correction_link_id' => $link->id]
        );

        return back()->with('success', 'Correccion enviada correctamente.');
    }

    private function resolveLink(string $year, string $division, string $club, string $token): CorrectionLink
    {
        $link = CorrectionLink::query()
            ->with(['season', 'division', 'club'])
            ->where('token', $token)
            ->firstOrFail();

        abort_unless($link->isValidToken($token), 403, 'Token invalido o inactivo.');
        abort_unless((string) $link->season->year === $year, 404);
        abort_unless($link->division->slug === $division, 404);
        abort_unless($link->club->slug === $club, 404);

        return $link;
    }
}
