<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::query()->create([
            'name' => trim(strip_tags($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'email_verified_at' => now(),
        ]);

        $this->auditLogger->log($request->user(), 'admin_user_created', 'user', $user->id, 'Usuario administrador creado.');

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:120', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $newRole = $validated['role'];
        $newActiveState = (bool) ($validated['is_active'] ?? false);

        if ($user->isSuperAdmin() && (! $newActiveState || $newRole !== User::ROLE_SUPER_ADMIN)) {
            $otherSuperAdmins = User::query()
                ->where('id', '!=', $user->id)
                ->where('role', User::ROLE_SUPER_ADMIN)
                ->where('is_active', true)
                ->count();

            if ($otherSuperAdmins === 0) {
                return back()->withErrors([
                    'role' => 'No se puede desactivar ni quitar el ultimo super admin activo.',
                ])->withInput();
            }
        }

        $payload = [
            'name' => trim(strip_tags($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'role' => $newRole,
            'is_active' => $newActiveState,
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        $this->auditLogger->log($request->user(), 'admin_user_updated', 'user', $user->id, 'Usuario administrador actualizado.');

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ((int) request()->user()->id === (int) $user->id) {
            return back()->withErrors(['user' => 'No puedes eliminar tu propio usuario en sesion.']);
        }

        if ($user->isSuperAdmin()) {
            $otherSuperAdmins = User::query()
                ->where('id', '!=', $user->id)
                ->where('role', User::ROLE_SUPER_ADMIN)
                ->where('is_active', true)
                ->count();

            if ($otherSuperAdmins === 0) {
                return back()->withErrors(['user' => 'No se puede eliminar el ultimo super admin activo.']);
            }
        }

        $userId = $user->id;
        $user->delete();

        $this->auditLogger->log(request()->user(), 'admin_user_deleted', 'user', $userId, 'Usuario administrador eliminado.');

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado.');
    }
}
