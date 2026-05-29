<x-admin-layout>
    <x-slot name="heading">Usuarios</x-slot>

    <div class="flex justify-end">
        <a href="{{ route('admin.users.create') }}" class="btn-primary">Nuevo usuario</a>
    </div>

    <x-card>
        @if($users->isEmpty())
            <x-empty-state title="Sin usuarios" description="Crea administradores autorizados para operar el panel." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Correo</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-semibold">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->role }}</td>
                            <td class="px-4 py-3"><x-badge :tone="$user->is_active ? 'success' : 'muted'">{{ $user->is_active ? 'Activo' : 'Inactivo' }}</x-badge></td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-sky-700 hover:underline">Editar</a>
                                <form method="POST" class="inline" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-2 text-red-700 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <div class="mt-4">{{ $users->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
