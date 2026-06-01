@extends('layouts.app')
@section('title', $project->name)
@section('header', $project->name)

@section('header-actions')
    <a href="{{ route('kanban.index', ['project' => $project->id]) }}" class="btn-secondary">Ver Kanban</a>
    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn-primary">+ Tarea</a>
@endsection

@section('content')
<div class="mb-6 bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-5">
    <span class="w-14 h-14 rounded-xl flex items-center justify-center text-white font-bold text-2xl"
          style="background:{{ $project->color }}">{{ strtoupper(substr($project->name,0,1)) }}</span>
    <div>
        <p class="text-gray-600 text-sm">{{ $project->description ?? 'Sin descripción' }}</p>
        <p class="text-xs text-gray-400 mt-1">Creado por {{ $project->creator->name }}</p>
    </div>
    <div class="ml-auto text-right">
        <p class="text-3xl font-bold text-gray-900">{{ $project->progress }}%</p>
        <p class="text-xs text-gray-400">completado</p>
    </div>
</div>

@foreach(['backlog'=>'Backlog','todo'=>'To Do','doing'=>'Doing','done'=>'Done'] as $status => $label)
@php $tasks = $tasksByStatus->get($status, collect()); @endphp
<div class="mb-6">
    <h2 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
        {{ $label }} <span class="text-xs bg-gray-200 text-gray-500 rounded-full px-2 py-0.5">{{ $tasks->count() }}</span>
    </h2>
    @if($tasks->isEmpty())
        <p class="text-sm text-gray-400 pl-2">Sin tareas en este estado</p>
    @else
    <div class="space-y-2">
        @foreach($tasks as $task)
        <div class="bg-white rounded-lg border border-gray-200 px-4 py-3 flex items-center gap-4">
            <span class="w-2 h-2 rounded-full flex-shrink-0
                @if($task->priority=='critical') bg-red-500
                @elseif($task->priority=='high') bg-orange-500
                @elseif($task->priority=='medium') bg-yellow-500
                @else bg-green-500
                @endif"></span>
            <a href="{{ route('tasks.show', $task) }}" class="flex-1 text-sm font-medium text-gray-800 hover:text-indigo-600">{{ $task->title }}</a>
            @if($task->assignee)
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                 style="background:{{ $task->assignee->avatar_color ?? '#6366f1' }}" title="{{ $task->assignee->name }}">
                {{ $task->assignee->initials }}
            </div>
            @endif
            <span class="text-xs text-gray-400">{{ $task->total_hours }}h</span>
            @if($task->due_date)
            <span class="text-xs {{ $task->is_overdue ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                {{ $task->due_date->format('d M') }}
            </span>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endforeach

<style>
.btn-primary{background:#4f46e5;color:#fff;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;text-decoration:none;}
.btn-primary:hover{background:#4338ca;}
.btn-secondary{background:#fff;color:#374151;border:1px solid #d1d5db;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;text-decoration:none;}
.btn-secondary:hover{background:#f9fafb;}
</style>
@endsection
