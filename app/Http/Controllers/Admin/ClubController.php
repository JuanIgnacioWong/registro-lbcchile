<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClubRequest;
use App\Http\Requests\UpdateClubRequest;
use App\Models\Club;
use App\Models\Division;
use App\Models\Season;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function index(): View
    {
        return view('admin.clubs.index', [
            'clubs' => Club::query()->with(['season', 'division'])->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.clubs.create', [
            'seasons' => Season::query()->orderByDesc('year')->get(),
            'divisions' => Division::query()->with('season')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreClubRequest $request): RedirectResponse
    {
        $name = trim(strip_tags((string) $request->string('name')));
        $slug = Str::slug((string) $request->string('slug'));

        $logoPath = $request->hasFile('logo_path')
            ? $request->file('logo_path')->store('clubs/reference', 'local')
            : null;

        $club = Club::query()->create([
            'season_id' => $request->integer('season_id'),
            'division_id' => $request->integer('division_id'),
            'name' => $name,
            'slug' => $slug,
            'logo_path' => $logoPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogger->log($request->user(), 'club_created', 'club', $club->id, 'Club creado.');

        return redirect()->route('admin.clubs.index')->with('success', 'Club creado correctamente.');
    }

    public function edit(Club $club): View
    {
        return view('admin.clubs.edit', [
            'club' => $club,
            'seasons' => Season::query()->orderByDesc('year')->get(),
            'divisions' => Division::query()->with('season')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateClubRequest $request, Club $club): RedirectResponse
    {
        $payload = [
            'season_id' => $request->integer('season_id'),
            'division_id' => $request->integer('division_id'),
            'name' => trim(strip_tags((string) $request->string('name'))),
            'slug' => Str::slug((string) $request->string('slug')),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('logo_path')) {
            if ($club->logo_path && Storage::disk('local')->exists($club->logo_path)) {
                Storage::disk('local')->delete($club->logo_path);
            }

            $payload['logo_path'] = $request->file('logo_path')->store('clubs/reference', 'local');
        }

        $club->update($payload);

        $this->auditLogger->log($request->user(), 'club_updated', 'club', $club->id, 'Club actualizado.');

        return redirect()->route('admin.clubs.index')->with('success', 'Club actualizado.');
    }

    public function destroy(Club $club): RedirectResponse
    {
        $clubId = $club->id;

        if ($club->logo_path && Storage::disk('local')->exists($club->logo_path)) {
            Storage::disk('local')->delete($club->logo_path);
        }

        $club->delete();

        $this->auditLogger->log(request()->user(), 'club_deleted', 'club', $clubId, 'Club eliminado.');

        return redirect()->route('admin.clubs.index')->with('success', 'Club eliminado.');
    }
}
