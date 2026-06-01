<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->query('project');
        $projects  = Project::all();

        $query = Task::with(['assignee', 'project', 'timeLogs']);
        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $tasks = $query->orderBy('order')->get();

        $columns = [
            'backlog' => ['label' => 'Backlog',  'color' => 'slate',  'tasks' => $tasks->where('status', 'backlog')->values()],
            'todo'    => ['label' => 'To Do',     'color' => 'blue',   'tasks' => $tasks->where('status', 'todo')->values()],
            'doing'   => ['label' => 'Doing',     'color' => 'amber',  'tasks' => $tasks->where('status', 'doing')->values()],
            'done'    => ['label' => 'Done',      'color' => 'green',  'tasks' => $tasks->where('status', 'done')->values()],
        ];

        return view('kanban.index', compact('columns', 'projects', 'projectId'));
    }

    public function moveTask(Request $request, Task $task)
    {
        $request->validate(['status' => 'required|in:backlog,todo,doing,done']);
        $task->update(['status' => $request->status]);
        return response()->json(['ok' => true, 'status' => $task->status]);
    }
}
