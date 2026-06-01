<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projects = Project::with('tasks')->latest()->get();

        $myTasks = Task::with(['project', 'assignee'])
            ->where('assigned_to', $user->id)
            ->whereNotIn('status', ['done'])
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $stats = [
            'total_projects' => Project::count(),
            'total_tasks'    => Task::count(),
            'done_tasks'     => Task::where('status', 'done')->count(),
            'overdue_tasks'  => Task::whereNotIn('status', ['done'])
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->count(),
        ];

        $recentLogs = TimeLog::with(['task.project', 'user'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('projects', 'myTasks', 'stats', 'recentLogs'));
    }
}
