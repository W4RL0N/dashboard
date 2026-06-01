@extends('layouts.app')
@section('title', 'Nuevo Proyecto')
@section('header', 'Nuevo Proyecto')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl border border-gray-200 p-6">
    <form method="POST" action="{{ route('projects.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="description" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="color" name="color" value="{{ old('color', '#6366f1') }}"
                   class="h-10 w-24 border border-gray-300 rounded-lg cursor-pointer">
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Crear Proyecto</button>
            <a href="{{ route('projects.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
        </div>
    </form>
</div>
<style>.btn-primary{background:#4f46e5;color:#fff;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;border:none;cursor:pointer;text-decoration:none;}.btn-primary:hover{background:#4338ca;}</style>
@endsection
