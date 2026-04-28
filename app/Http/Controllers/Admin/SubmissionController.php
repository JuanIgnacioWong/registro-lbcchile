<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSubmissionPaymentStatusRequest;
use App\Models\Season;
use App\Models\Submission;
use App\Models\SubmissionVersion;
use App\Services\AuditLogger;
use App\Services\SubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function __construct(
        private readonly AuditLogger $auditLogger,
        private readonly SubmissionService $submissionService,
    ) {
    }

    public function index(Request $request): View
    {
        $query = Submission::query()
            ->with(['season', 'division', 'club'])
            ->withCount('versions');

        if ($request->filled('season_id')) {
            $query->where('season_id', (int) $request->query('season_id'));
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', (int) $request->query('division_id'));
        }

        if ($request->filled('club_id')) {
            $query->where('club_id', (int) $request->query('club_id'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', (string) $request->query('payment_status'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', (string) $request->query('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', (string) $request->query('to_date'));
        }

        return view('admin.submissions.index', [
            'submissions' => $query->latest()->paginate(20)->withQueryString(),
            'seasons' => Season::query()->orderByDesc('year')->get(),
        ]);
    }

    public function show(Submission $submission): View
    {
        $submission->load(['season', 'division', 'club', 'versions' => fn ($q) => $q->orderByDesc('version_number')]);

        return view('admin.submissions.show', compact('submission'));
    }

    public function updatePaymentStatus(UpdateSubmissionPaymentStatusRequest $request, Submission $submission): RedirectResponse
    {
        $submission->update([
            'payment_status' => $request->string('payment_status')->toString(),
        ]);

        $this->auditLogger->log(
            $request->user(),
            'payment_status_updated',
            'submission',
            $submission->id,
            'Estado de pago modificado.',
            ['payment_status' => $submission->payment_status]
        );

        return back()->with('success', 'Estado de pago actualizado.');
    }

    public function enableExtraSubmission(Request $request, Submission $submission): RedirectResponse
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:300'],
        ]);

        if ($submission->max_allowed_submissions >= 4) {
            return back()->withErrors(['max_allowed_submissions' => 'El maximo permitido es 4 envios.']);
        }

        $submission->increment('max_allowed_submissions');

        $this->auditLogger->log(
            $request->user(),
            'extra_submission_enabled',
            'submission',
            $submission->id,
            'Se habilito un cupo extra de envio.',
            [
                'new_limit' => $submission->fresh()->max_allowed_submissions,
                'reason' => $request->input('reason'),
            ]
        );

        return back()->with('success', 'Cupo extra habilitado correctamente.');
    }

    public function acceptVersion(Request $request, SubmissionVersion $version): RedirectResponse
    {
        $this->submissionService->markAccepted($version);

        $this->auditLogger->log($request->user(), 'version_accepted', 'submission_version', $version->id, 'Version aceptada.');

        return back()->with('success', 'Version aceptada y marcada como activa.');
    }

    public function rejectVersion(Request $request, SubmissionVersion $version): RedirectResponse
    {
        $this->submissionService->markRejected($version);

        $this->auditLogger->log($request->user(), 'version_rejected', 'submission_version', $version->id, 'Version rechazada.');

        return back()->with('success', 'Version rechazada.');
    }

    public function destroyVersion(Request $request, SubmissionVersion $version): RedirectResponse
    {
        $versionId = $version->id;

        $this->submissionService->deleteVersion($version);

        $this->auditLogger->log($request->user(), 'version_deleted', 'submission_version', $versionId, 'Version eliminada.');

        return back()->with('success', 'Version eliminada correctamente.');
    }
}
