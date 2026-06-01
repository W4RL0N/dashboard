@extends('layouts.app')
@section('title', $task->title)
@section('header', $task->title)

@section('header-actions')
    <a href="{{ route('tasks.edit', $task) }}" class="btn-secondary">Editar</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main info --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                    @if($task->status=='backlog') bg-gray-100 text-gray-600
                    @elseif($task->status=='todo') bg-blue-100 text-blue-700
                    @elseif($task->status=='doing') bg-amber-100 text-amber-700
                    @else bg-green-100 text-green-700
                    @endif">
                    {{ ['backlog'=>'Backlog','todo'=>'To Do','doing'=>'Doing','done'=>'Done'][$task->status] }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                    @if($task->priority=='critical') bg-red-100 text-red-700
                    @elseif($task->priority=='high') bg-orange-100 text-orange-700
                    @elseif($task->priority=='medium') bg-yellow-100 text-yellow-700
                    @else bg-green-100 text-green-700
                    @endif">
                    {{ ucfirst($task->priority) }}
                </span>
                @if($task->is_overdue)
                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Vencida</span>
                @endif
            </div>

            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                {{ $task->description ?? 'Sin descripción.' }}
            </p>

            <div class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Proyecto</p>
                    <a href="{{ route('projects.show', $task->project) }}" class="text-indigo-600 hover:underline font-medium">{{ $task->project->name }}</a>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Creado por</p>
                    <p class="text-gray-700 font-medium">{{ $task->creator->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Fecha inicio</p>
                    <p class="text-gray-700">{{ $task->start_date?->format('d M Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Fecha vencimiento</p>
                    <p class="{{ $task->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $task->due_date?->format('d M Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Horas estimadas</p>
                    <p class="text-gray-700">{{ $task->estimated_hours ?? '—' }}h</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Horas registradas</p>
                    <p class="text-indigo-600 font-semibold">{{ $task->total_hours }}h</p>
                </div>
            </div>
        </div>

        {{-- Time logs --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Registro de tiempo</h2>

            <form method="POST" action="{{ route('time-logs.store', $task) }}" class="flex gap-3 mb-5 flex-wrap">
                @csrf
                <input type="number" name="hours" placeholder="Horas (ej. 1.5)" step="0.25" min="0.25" max="24" required
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-36 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <input type="date" name="logged_at" value="{{ date('Y-m-d') }}" required
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <input type="text" name="note" placeholder="Nota (opcional)"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1 min-w-40 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <button type="submit" class="btn-primary">Registrar</button>
            </form>

            @if($task->timeLogs->isEmpty())
                <p class="text-sm text-gray-400">Sin registros de tiempo.</p>
            @else
            <div class="space-y-2">
                @foreach($task->timeLogs->sortByDesc('logged_at') as $log)
                <div class="flex items-center gap-3 text-sm py-2 border-b border-gray-50 last:border-0">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background:{{ $log->user->avatar_color ?? '#6366f1' }}">
                        {{ $log->user->initials }}
                    </div>
                    <div class="flex-1">
                        <span class="font-medium text-gray-700">{{ $log->user->name }}</span>
                        @if($log->note) <span class="text-gray-400"> · {{ $log->note }}</span> @endif
                    </div>
                    <span class="font-semibold text-indigo-600">{{ $log->hours }}h</span>
                    <span class="text-gray-400 text-xs">{{ $log->logged_at->format('d M Y') }}</span>
                    @if($log->user_id === auth()->id() || auth()->user()->hasRole('admin'))
                    <form method="POST" action="{{ route('time-logs.destroy', $log) }}" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Sidebar info --}}
    <div class="space-y-4">
        {{-- Assignee --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-3 text-sm">Asignado a</h3>
            @if($task->assignee)
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
                     style="background:{{ $task->assignee->avatar_color ?? '#6366f1' }}">
                    {{ $task->assignee->initials }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $task->assignee->name }}</p>
                    <p class="text-xs text-gray-400">{{ $task->assignee->email }}</p>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-400">Sin asignar</p>
            @endif
        </div>

        {{-- Progress --}}
        @if($task->estimated_hours)
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-3 text-sm">Tiempo</h3>
            @php $pct = min(100, round(($task->total_hours / $task->estimated_hours) * 100)); @endphp
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>{{ $task->total_hours }}h registradas</span>
                <span>{{ $task->estimated_hours }}h estimadas</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="h-2.5 rounded-full {{ $pct > 100 ? 'bg-red-500' : 'bg-indigo-500' }}" style="width:{{ $pct }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ $pct }}% del tiempo estimado</p>
        </div>
        @endif

        {{-- Quick status change --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-3 text-sm">Mover estado</h3>
            <div class="space-y-2">
                @foreach(['backlog'=>'Backlog','todo'=>'To Do','doing'=>'Doing','done'=>'Done'] as $s=>$l)
                @if($s !== $task->status)
                <form method="POST" action="{{ route('tasks.status', $task) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $s }}">
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-indigo-50 hover:text-indigo-700 text-gray-600 border border-gray-100 transition">
                        → {{ $l }}
                    </button>
                </form>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary{background:#4f46e5;color:#fff;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;border:none;cursor:pointer;text-decoration:none;}
.btn-primary:hover{background:#4338ca;}
.btn-secondary{background:#fff;color:#374151;border:1px solid #d1d5db;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;text-decoration:none;}
.btn-secondary:hover{background:#f9fafb;}
</style>
@endsection
