<x-admin-layout>
    <x-slot name="heading">Usuarios</x-slot>

    <div class="mb-4 flex justify-end"><a href="{{ route('admin.users.create') }}" class="rounded bg-red-600 px-4 py-2 text-white">Nuevo usuario</a></div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Nombre</th><th class="px-4 py-3 text-left">Correo</th><th class="px-4 py-3 text-left">Rol</th><th class="px-4 py-3 text-center">Acciones</th></tr></thead>
            <tbody>
            @forelse($users as $user)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ $user->role }}</td>
                    <td class="px-4 py-3 text-center">
                        <a class="text-blue-600" href="{{ route('admin.users.edit', $user) }}">Editar</a>
                        <form method="POST" class="inline" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('DELETE')<button class="ml-2 text-red-600" onclick="return confirm('Eliminar usuario?')">Eliminar</button></form>
                    </td>
                </tr>
            @empty
                <tr><td class="px-4 py-4 text-slate-500" colspan="4">Sin usuarios.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-admin-layout>
