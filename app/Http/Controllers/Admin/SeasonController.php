<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeasonRequest;
use App\Http\Requests\UpdateSeasonRequest;
use App\Models\Season;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeasonController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function index(): View
    {
        return view('admin.seasons.index', [
            'seasons' => Season::query()->orderByDesc('year')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.seasons.create');
    }

    public function store(StoreSeasonRequest $request): RedirectResponse
    {
        $season = Season::query()->create([
            'year' => $request->integer('year'),
            'name' => trim(strip_tags((string) $request->string('name'))),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogger->log($request->user(), 'season_created', 'season', $season->id, 'Temporada creada.');

        return redirect()->route('admin.seasons.index')->with('success', 'Temporada creada correctamente.');
    }

    public function edit(Season $season): View
    {
        return view('admin.seasons.edit', compact('season'));
    }

    public function update(UpdateSeasonRequest $request, Season $season): RedirectResponse
    {
        $season->update([
            'year' => $request->integer('year'),
            'name' => trim(strip_tags((string) $request->string('name'))),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogger->log($request->user(), 'season_updated', 'season', $season->id, 'Temporada actualizada.');

        return redirect()->route('admin.seasons.index')->with('success', 'Temporada actualizada.');
    }

    public function destroy(Season $season): RedirectResponse
    {
        $seasonId = $season->id;
        $season->delete();

        $this->auditLogger->log(request()->user(), 'season_deleted', 'season', $seasonId, 'Temporada eliminada.');

        return redirect()->route('admin.seasons.index')->with('success', 'Temporada eliminada.');
    }
}
