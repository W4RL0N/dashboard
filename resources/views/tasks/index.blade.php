@extends('layouts.app')
@section('title', 'Backlog')
@section('header', 'Backlog de Tareas')

@section('header-actions')
    <a href="{{ route('tasks.create') }}" class="btn-primary">+ Nueva Tarea</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
        <form method="GET" class="flex gap-2 flex-wrap">
            <select name="status" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none">
                <option value="">Todos los estados</option>
                @foreach(['backlog'=>'Backlog','todo'=>'To Do','doing'=>'Doing','done'=>'Done'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('status')==$v ? 'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
            <select name="project_id" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none">
                <option value="">Todos los proyectos</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ request('project_id')==$p->id ? 'selected':'' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="px-5 py-3 font-medium text-gray-500">Tarea</th>
                <th class="px-3 py-3 font-medium text-gray-500">Proyecto</th>
                <th class="px-3 py-3 font-medium text-gray-500">Estado</th>
                <th class="px-3 py-3 font-medium text-gray-500">Prioridad</th>
                <th class="px-3 py-3 font-medium text-gray-500">Asignado</th>
                <th class="px-3 py-3 font-medium text-gray-500">Inicio</th>
                <th class="px-3 py-3 font-medium text-gray-500">Vence</th>
                <th class="px-3 py-3 font-medium text-gray-500">Tiempo</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($tasks as $task)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                        {{ $task->title }}
                    </a>
                </td>
                <td class="px-3 py-3 text-gray-500">{{ $task->project->name }}</td>
                <td class="px-3 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if($task->status=='backlog') bg-gray-100 text-gray-600
                        @elseif($task->status=='todo') bg-blue-100 text-blue-700
                        @elseif($task->status=='doing') bg-amber-100 text-amber-700
                        @else bg-green-100 text-green-700
                        @endif">
                        {{ ['backlog'=>'Backlog','todo'=>'To Do','doing'=>'Doing','done'=>'Done'][$task->status] }}
                    </span>
                </td>
                <td class="px-3 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if($task->priority=='critical') bg-red-100 text-red-700
                        @elseif($task->priority=='high') bg-orange-100 text-orange-700
                        @elseif($task->priority=='medium') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700
                        @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                </td>
                <td class="px-3 py-3">
                    @if($task->assignee)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold"
                             style="background:{{ $task->assignee->avatar_color ?? '#6366f1' }}">
                            {{ $task->assignee->initials }}
                        </div>
                        <span class="text-gray-600">{{ $task->assignee->name }}</span>
                    </div>
                    @else<span class="text-gray-300">—</span>@endif
                </td>
                <td class="px-3 py-3 text-gray-400 text-xs">{{ $task->start_date?->format('d M') ?? '—' }}</td>
                <td class="px-3 py-3 text-xs {{ $task->is_overdue ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                    {{ $task->due_date?->format('d M Y') ?? '—' }}
                </td>
                <td class="px-3 py-3 text-gray-500">{{ $task->total_hours }}h</td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">No hay tareas</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-5 py-3 border-t border-gray-100">
        {{ $tasks->links() }}
    </div>
</div>

<style>.btn-primary{background:#4f46e5;color:#fff;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;text-decoration:none;}.btn-primary:hover{background:#4338ca;}</style>
@endsection
