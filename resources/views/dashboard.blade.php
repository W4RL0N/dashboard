@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('header-actions')
    <a href="{{ route('tasks.create') }}" class="btn-primary">
        + Nueva Tarea
    </a>
@endsection

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Proyectos</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_projects'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Tareas totales</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_tasks'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Completadas</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['done_tasks'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Vencidas</p>
        <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['overdue_tasks'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Projects progress --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-900">Proyectos</h2>
            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:underline">Ver todos</a>
        </div>
        @forelse($projects as $project)
        <div class="mb-4">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:{{ $project->color }}"></span>
                    <a href="{{ route('projects.show', $project) }}" class="text-sm font-medium text-gray-800 hover:text-indigo-600">{{ $project->name }}</a>
                </div>
                <span class="text-xs text-gray-500">{{ $project->progress }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="h-2 rounded-full transition-all" style="width:{{ $project->progress }}%; background:{{ $project->color }}"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ $project->tasks->count() }} tareas · {{ $project->tasks->where('status','done')->count() }} completadas</p>
        </div>
        @empty
        <p class="text-sm text-gray-400">No hay proyectos aún. <a href="{{ route('projects.create') }}" class="text-indigo-600 hover:underline">Crear uno</a></p>
        @endforelse
    </div>

    {{-- My tasks --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Mis tareas pendientes</h2>
        @forelse($myTasks as $task)
        <a href="{{ route('tasks.show', $task) }}" class="block mb-3 p-3 rounded-lg border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition">
            <div class="flex items-start gap-2">
                <span class="mt-0.5 w-2 h-2 rounded-full flex-shrink-0 bg-{{ $task->priority_color }}-400"></span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $task->title }}</p>
                    <p class="text-xs text-gray-400">{{ $task->project->name }}</p>
                    @if($task->due_date)
                        <p class="text-xs {{ $task->is_overdue ? 'text-red-500' : 'text-gray-400' }} mt-0.5">
                            Vence {{ $task->due_date->format('d M') }}
                        </p>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <p class="text-sm text-gray-400">No tienes tareas pendientes 🎉</p>
        @endforelse
    </div>
</div>

{{-- Recent time logs --}}
<div class="mt-6 bg-white rounded-xl border border-gray-200 p-6">
    <h2 class="font-semibold text-gray-900 mb-4">Registro de tiempo reciente</h2>
    @if($recentLogs->isEmpty())
        <p class="text-sm text-gray-400">Sin registros de tiempo aún.</p>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="pb-2 font-medium">Usuario</th>
                    <th class="pb-2 font-medium">Tarea</th>
                    <th class="pb-2 font-medium">Proyecto</th>
                    <th class="pb-2 font-medium">Horas</th>
                    <th class="pb-2 font-medium">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentLogs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="py-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                 style="background:{{ $log->user->avatar_color ?? '#6366f1' }}">
                                {{ $log->user->initials }}
                            </div>
                            {{ $log->user->name }}
                        </div>
                    </td>
                    <td class="py-2 text-gray-700">{{ Str::limit($log->task->title, 40) }}</td>
                    <td class="py-2 text-gray-500">{{ $log->task->project->name }}</td>
                    <td class="py-2 font-semibold text-indigo-600">{{ $log->hours }}h</td>
                    <td class="py-2 text-gray-400">{{ $log->logged_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<style>
.btn-primary {
    background: #4f46e5;
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s;
}
.btn-primary:hover { background: #4338ca; }
</style>
@endsection
