@extends('layouts.app')
@section('title', 'Proyectos')
@section('header', 'Proyectos')

@section('header-actions')
    <a href="{{ route('projects.create') }}" class="btn-primary">+ Nuevo Proyecto</a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($projects as $project)
    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                      style="background:{{ $project->color }}">
                    {{ strtoupper(substr($project->name, 0, 1)) }}
                </span>
                <div>
                    <a href="{{ route('projects.show', $project) }}" class="font-semibold text-gray-900 hover:text-indigo-600">{{ $project->name }}</a>
                    <p class="text-xs text-gray-400">por {{ $project->creator->name }}</p>
                </div>
            </div>
            @role('admin')
            <div class="flex gap-1">
                <a href="{{ route('projects.edit', $project) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
            </div>
            @endrole
        </div>

        @if($project->description)
        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $project->description }}</p>
        @endif

        {{-- Progress --}}
        <div class="mb-3">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>Progreso</span><span>{{ $project->progress }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="h-2 rounded-full" style="width:{{ $project->progress }}%; background:{{ $project->color }}"></div>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-gray-400">
            <span>{{ $project->tasks->count() }} tareas</span>
            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 font-medium hover:underline">Ver tablero →</a>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 text-gray-400">
        <p class="text-lg mb-2">No hay proyectos aún</p>
        <a href="{{ route('projects.create') }}" class="btn-primary">Crear primer proyecto</a>
    </div>
    @endforelse
</div>

<style>
.btn-primary { background:#4f46e5; color:#fff; padding:0.5rem 1rem; border-radius:0.5rem; font-size:0.875rem; font-weight:500; text-decoration:none; }
.btn-primary:hover { background:#4338ca; }
</style>
@endsection
