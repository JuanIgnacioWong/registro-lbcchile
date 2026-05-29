<x-admin-layout>
    <x-slot name="heading">Editar Usuario</x-slot>

    <x-card title="Datos de acceso">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-2xl space-y-4">
            @csrf
            @method('PUT')
            <x-input label="Nombre" name="name" :value="old('name', $user->name)" required />
            <x-input type="email" label="Correo" name="email" :value="old('email', $user->email)" required />
            <x-select label="Rol" name="role" required>
                <option value="super_admin" @selected(old('role', $user->role) === 'super_admin')>super_admin</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
            </x-select>
            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}> Usuario activo</label>
            <x-input type="password" label="Nueva contraseña (opcional)" name="password" />
            <x-input type="password" label="Confirmar contraseña" name="password_confirmation" />
            <x-button type="submit">Actualizar usuario</x-button>
        </form>
    </x-card>
</x-admin-layout>
