@extends('layouts.app')
@section('title', 'Editar Proyecto')
@section('header', 'Editar: ' . $project->name)

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl border border-gray-200 p-6">
    <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
            <input type="text" name="name" value="{{ old('name', $project->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="description" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('description', $project->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="color" name="color" value="{{ old('color', $project->color) }}"
                   class="h-10 w-24 border border-gray-300 rounded-lg cursor-pointer">
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Guardar</button>
            <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
        </div>
    </form>
    <form method="POST" action="{{ route('projects.destroy', $project) }}" class="mt-6 pt-6 border-t border-gray-100"
          onsubmit="return confirm('¿Eliminar este proyecto y todas sus tareas?')">
        @csrf @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Eliminar proyecto</button>
    </form>
</div>
<style>.btn-primary{background:#4f46e5;color:#fff;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;border:none;cursor:pointer;}.btn-primary:hover{background:#4338ca;}</style>
@endsection
