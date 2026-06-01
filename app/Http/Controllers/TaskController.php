<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignee']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $tasks    = $query->orderBy('status')->orderBy('order')->paginate(20)->withQueryString();
        $projects = Project::all();
        $users    = User::all();
        return view('tasks.index', compact('tasks', 'projects', 'users'));
    }

    public function create()
    {
        $projects = Project::all();
        $users    = User::all();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'status'          => 'required|in:backlog,todo,doing,done',
            'priority'        => 'required|in:low,medium,high,critical',
            'project_id'      => 'required|exists:projects,id',
            'assigned_to'     => 'nullable|exists:users,id',
            'start_date'      => 'nullable|date',
            'due_date'        => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|integer|min:1',
        ]);
        $data['created_by'] = Auth::id();
        Task::create($data);

        return redirect()->back()->with('success', 'Tarea creada.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee', 'creator', 'timeLogs.user']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $users    = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'status'          => 'required|in:backlog,todo,doing,done',
            'priority'        => 'required|in:low,medium,high,critical',
            'project_id'      => 'required|exists:projects,id',
            'assigned_to'     => 'nullable|exists:users,id',
            'start_date'      => 'nullable|date',
            'due_date'        => 'nullable|date',
            'estimated_hours' => 'nullable|integer|min:1',
        ]);
        $task->update($data);

        return redirect()->back()->with('success', 'Tarea actualizada.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate(['status' => 'required|in:backlog,todo,doing,done']);
        $task->update(['status' => $request->status]);
        return response()->json(['ok' => true]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tarea eliminada.');
    }
}
