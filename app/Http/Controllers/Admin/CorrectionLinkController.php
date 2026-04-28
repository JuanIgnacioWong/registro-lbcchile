<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateCorrectionLinkRequest;
use App\Models\Club;
use App\Models\CorrectionLink;
use App\Models\Division;
use App\Models\Season;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CorrectionLinkController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function index(): View
    {
        return view('admin.corrections.index', [
            'seasons' => Season::query()->orderByDesc('year')->get(),
            'divisions' => Division::query()->with('season')->orderBy('name')->get(),
            'clubs' => Club::query()->with(['season', 'division'])->orderBy('name')->get(),
            'links' => CorrectionLink::query()
                ->with(['season', 'division', 'club'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function store(GenerateCorrectionLinkRequest $request): RedirectResponse
    {
        $link = CorrectionLink::query()->create([
            'season_id' => $request->integer('season_id'),
            'division_id' => $request->integer('division_id'),
            'club_id' => $request->integer('club_id'),
            'token' => Str::random(64),
            'is_active' => true,
            'expires_at' => $request->input('expires_at'),
        ]);

        $this->auditLogger->log($request->user(), 'correction_link_created', 'correction_link', $link->id, 'Enlace de correccion generado.');

        return back()->with('success', 'Enlace de correccion generado correctamente.');
    }

    public function toggle(Request $request, CorrectionLink $correctionLink): RedirectResponse
    {
        $correctionLink->update([
            'is_active' => ! $correctionLink->is_active,
        ]);

        $this->auditLogger->log(
            $request->user(),
            'correction_link_toggled',
            'correction_link',
            $correctionLink->id,
            'Estado del enlace de correccion actualizado.',
            ['is_active' => $correctionLink->is_active]
        );

        return back()->with('success', 'Estado del enlace actualizado.');
    }

    public function regenerate(Request $request, CorrectionLink $correctionLink): RedirectResponse
    {
        $correctionLink->update([
            'token' => Str::random(64),
            'is_active' => true,
        ]);

        $this->auditLogger->log($request->user(), 'correction_link_regenerated', 'correction_link', $correctionLink->id, 'Token regenerado.');

        return back()->with('success', 'Token regenerado exitosamente.');
    }
}
