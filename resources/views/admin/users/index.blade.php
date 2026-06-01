@extends('layouts.app')
@section('title', 'Usuarios')
@section('header', 'Gestión de Usuarios')

@section('header-actions')
    <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Nuevo Usuario</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="px-5 py-3 font-medium text-gray-500">Usuario</th>
                <th class="px-3 py-3 font-medium text-gray-500">Email</th>
                <th class="px-3 py-3 font-medium text-gray-500">Rol</th>
                <th class="px-3 py-3 font-medium text-gray-500">Tareas asignadas</th>
                <th class="px-3 py-3 font-medium text-gray-500">Registrado</th>
                <th class="px-3 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                             style="background:{{ $user->avatar_color ?? '#6366f1' }}">
                            {{ $user->initials }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-3 py-3 text-gray-500">{{ $user->email }}</td>
                <td class="px-3 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->hasRole('admin') ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $user->getRoleNames()->first() ?? '—' }}
                    </span>
                </td>
                <td class="px-3 py-3 text-gray-500">{{ $user->assignedTasks->count() }}</td>
                <td class="px-3 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-3 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-indigo-600 hover:underline">Editar</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar usuario?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline">Eliminar</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<style>.btn-primary{background:#4f46e5;color:#fff;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;text-decoration:none;}.btn-primary:hover{background:#4338ca;}</style>
@endsection
