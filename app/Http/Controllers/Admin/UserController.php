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
            'role' => ['required', Rule::in(['admin'])],
        ]);

        $user = User::query()->create([
            'name' => trim(strip_tags($validated['name'])),
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
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
            'role' => ['required', Rule::in(['admin'])],
        ]);

        $payload = [
            'name' => trim(strip_tags($validated['name'])),
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
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

        $userId = $user->id;
        $user->delete();

        $this->auditLogger->log(request()->user(), 'admin_user_deleted', 'user', $userId, 'Usuario administrador eliminado.');

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado.');
    }
}
