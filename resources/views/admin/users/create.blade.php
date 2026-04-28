<x-admin-layout>
    <x-slot name="heading">Nuevo Usuario</x-slot>

    <form method="POST" action="{{ route('admin.users.store') }}" class="max-w-2xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
        @csrf
        <div><label class="text-sm font-medium">Nombre</label><input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Correo</label><input name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full rounded border-slate-300"></div>
        <input type="hidden" name="role" value="admin">
        <div><label class="text-sm font-medium">Contrasena</label><input name="password" type="password" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Confirmar contrasena</label><input name="password_confirmation" type="password" class="mt-1 w-full rounded border-slate-300"></div>
        <button class="rounded bg-red-600 px-4 py-2 text-white">Crear usuario</button>
    </form>
</x-admin-layout>
