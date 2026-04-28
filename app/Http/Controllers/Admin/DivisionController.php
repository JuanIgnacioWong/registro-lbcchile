<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\Division;
use App\Models\Season;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DivisionController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function index(): View
    {
        return view('admin.divisions.index', [
            'divisions' => Division::query()->with('season')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.divisions.create', [
            'seasons' => Season::query()->orderByDesc('year')->get(),
        ]);
    }

    public function store(StoreDivisionRequest $request): RedirectResponse
    {
        $name = trim(strip_tags((string) $request->string('name')));
        $slug = $request->string('slug')->toString() ?: Str::slug($name);

        $division = Division::query()->create([
            'season_id' => $request->integer('season_id'),
            'name' => $name,
            'slug' => Str::slug($slug),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogger->log($request->user(), 'division_created', 'division', $division->id, 'Division creada.');

        return redirect()->route('admin.divisions.index')->with('success', 'Division creada correctamente.');
    }

    public function edit(Division $division): View
    {
        return view('admin.divisions.edit', [
            'division' => $division,
            'seasons' => Season::query()->orderByDesc('year')->get(),
        ]);
    }

    public function update(UpdateDivisionRequest $request, Division $division): RedirectResponse
    {
        $name = trim(strip_tags((string) $request->string('name')));

        $division->update([
            'season_id' => $request->integer('season_id'),
            'name' => $name,
            'slug' => Str::slug((string) $request->string('slug')),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogger->log($request->user(), 'division_updated', 'division', $division->id, 'Division actualizada.');

        return redirect()->route('admin.divisions.index')->with('success', 'Division actualizada.');
    }

    public function destroy(Division $division): RedirectResponse
    {
        $id = $division->id;
        $division->delete();

        $this->auditLogger->log(request()->user(), 'division_deleted', 'division', $id, 'Division eliminada.');

        return redirect()->route('admin.divisions.index')->with('success', 'Division eliminada.');
    }
}
