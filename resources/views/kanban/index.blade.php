@extends('layouts.app')

@section('title', 'Tablero Kanban')
@section('header', 'Tablero Kanban')

@section('header-actions')
    <form method="GET" action="{{ route('kanban.index') }}" class="flex items-center gap-2">
        <select name="project" onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Todos los proyectos</option>
            @foreach($projects as $p)
                <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('tasks.create') }}" class="btn-primary">+ Nueva Tarea</a>
@endsection

@section('content')
<div class="flex gap-4 h-full overflow-x-auto pb-4" id="kanban-board">

    @foreach($columns as $status => $col)
    <div class="kanban-column flex flex-col w-72 flex-shrink-0 bg-gray-100 rounded-xl"
         data-status="{{ $status }}">

        {{-- Column header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full
                    @if($col['color']=='slate') bg-slate-400
                    @elseif($col['color']=='blue') bg-blue-500
                    @elseif($col['color']=='amber') bg-amber-500
                    @else bg-green-500
                    @endif"></span>
                <h3 class="font-semibold text-sm text-gray-700">{{ $col['label'] }}</h3>
                <span class="text-xs bg-gray-200 text-gray-500 rounded-full px-2 py-0.5">{{ $col['tasks']->count() }}</span>
            </div>
        </div>

        {{-- Cards container --}}
        <div class="kanban-tasks flex-1 overflow-y-auto p-3 space-y-2 min-h-20"
             data-status="{{ $status }}">

            @foreach($col['tasks'] as $task)
            <div class="kanban-card bg-white rounded-lg border border-gray-200 p-3 shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md transition"
                 data-id="{{ $task->id }}" draggable="true">

                {{-- Priority badge --}}
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        @if($task->priority=='critical') bg-red-100 text-red-700
                        @elseif($task->priority=='high') bg-orange-100 text-orange-700
                        @elseif($task->priority=='medium') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700
                        @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                    @if($task->is_overdue)
                        <span class="text-xs text-red-500 font-medium">Vencida</span>
                    @endif
                </div>

                {{-- Title --}}
                <a href="{{ route('tasks.show', $task) }}" class="block text-sm font-semibold text-gray-800 hover:text-indigo-600 leading-snug mb-2">
                    {{ $task->title }}
                </a>

                {{-- Project --}}
                <p class="text-xs text-gray-400 mb-3">{{ $task->project->name }}</p>

                {{-- Footer --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $task->total_hours }}h / {{ $task->estimated_hours ?? '?' }}h
                    </div>
                    @if($task->assignee)
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background:{{ $task->assignee->avatar_color ?? '#6366f1' }}"
                         title="{{ $task->assignee->name }}">
                        {{ $task->assignee->initials }}
                    </div>
                    @endif
                </div>

                @if($task->due_date)
                <div class="mt-2 pt-2 border-t border-gray-50 text-xs {{ $task->is_overdue ? 'text-red-500' : 'text-gray-400' }}">
                    Vence {{ $task->due_date->format('d M Y') }}
                </div>
                @endif
            </div>
            @endforeach

        </div>
    </div>
    @endforeach

</div>

<style>
.btn-primary {
    background: #4f46e5; color: #fff;
    padding: 0.5rem 1rem; border-radius: 0.5rem;
    font-size: 0.875rem; font-weight: 500;
    text-decoration: none; transition: background 0.15s;
}
.btn-primary:hover { background: #4338ca; }
.kanban-column { min-height: 500px; }
.kanban-tasks { min-height: 80px; }
.drag-over { background: #e0e7ff !important; border: 2px dashed #6366f1; }
.dragging { opacity: 0.4; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.kanban-card');
    const containers = document.querySelectorAll('.kanban-tasks');
    let draggingEl = null;

    cards.forEach(card => {
        card.addEventListener('dragstart', e => {
            draggingEl = card;
            setTimeout(() => card.classList.add('dragging'), 0);
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            draggingEl = null;
            document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
        });
    });

    containers.forEach(container => {
        container.addEventListener('dragover', e => {
            e.preventDefault();
            container.classList.add('drag-over');
        });
        container.addEventListener('dragleave', () => {
            container.classList.remove('drag-over');
        });
        container.addEventListener('drop', e => {
            e.preventDefault();
            container.classList.remove('drag-over');
            if (!draggingEl) return;

            const newStatus = container.dataset.status;
            const taskId    = draggingEl.dataset.id;
            container.appendChild(draggingEl);

            // Update counter badges
            document.querySelectorAll('.kanban-column').forEach(col => {
                const cnt = col.querySelector('.kanban-tasks').children.length;
                col.querySelector('span.rounded-full.bg-gray-200').textContent = cnt;
            });

            fetch(`/kanban/${taskId}/move`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status: newStatus }),
            });
        });
    });
});
</script>
@endsection
