<x-admin-layout>
    <x-slot name="heading">Nuevo Usuario</x-slot>

    <x-card title="Datos de acceso">
        <form method="POST" action="{{ route('admin.users.store') }}" class="max-w-2xl space-y-4">
            @csrf
            <x-input label="Nombre" name="name" :value="old('name')" required />
            <x-input type="email" label="Correo" name="email" :value="old('email')" required />
            <x-select label="Rol" name="role" required>
                <option value="super_admin" @selected(old('role') === 'super_admin')>super_admin</option>
                <option value="admin" @selected(old('role', 'admin') === 'admin')>admin</option>
            </x-select>
            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" checked> Usuario activo</label>
            <x-input type="password" label="Contraseña" name="password" required />
            <x-input type="password" label="Confirmar contraseña" name="password_confirmation" required />
            <x-button type="submit">Crear usuario</x-button>
        </form>
    </x-card>
</x-admin-layout>
