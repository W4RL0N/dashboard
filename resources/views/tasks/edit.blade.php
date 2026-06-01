@extends('layouts.app')
@section('title', 'Editar Tarea')
@section('header', 'Editar: ' . Str::limit($task->title, 50))

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-200 p-6">
    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('description', $task->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Proyecto *</label>
                <select name="project_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ old('project_id', $task->project_id)==$p->id ? 'selected':'' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asignado a</label>
                <select name="assigned_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">Sin asignar</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('assigned_to', $task->assigned_to)==$u->id ? 'selected':'' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @foreach(['backlog'=>'Backlog','todo'=>'To Do','doing'=>'Doing','done'=>'Done'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('status', $task->status)==$v ? 'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select name="priority" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @foreach(['low'=>'Baja','medium'=>'Media','high'=>'Alta','critical'=>'Crítica'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('priority', $task->priority)==$v ? 'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio</label>
                <input type="date" name="start_date" value="{{ old('start_date', $task->start_date?->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha vencimiento</label>
                <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Horas estimadas</label>
                <input type="number" name="estimated_hours" value="{{ old('estimated_hours', $task->estimated_hours) }}" min="1"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Guardar cambios</button>
            <a href="{{ route('tasks.show', $task) }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
        </div>
    </form>

    <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="mt-6 pt-6 border-t border-gray-100"
          onsubmit="return confirm('¿Eliminar esta tarea?')">
        @csrf @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Eliminar tarea</button>
    </form>
</div>
<style>.btn-primary{background:#4f46e5;color:#fff;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;border:none;cursor:pointer;}.btn-primary:hover{background:#4338ca;}</style>
@endsection
