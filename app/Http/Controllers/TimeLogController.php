<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $data = $request->validate([
            'hours'     => 'required|numeric|min:0.25|max:24',
            'note'      => 'nullable|string|max:500',
            'logged_at' => 'required|date|before_or_equal:today',
        ]);
        $data['task_id'] = $task->id;
        $data['user_id'] = Auth::id();

        TimeLog::create($data);

        return redirect()->back()->with('success', 'Tiempo registrado.');
    }

    public function destroy(TimeLog $timeLog)
    {
        $timeLog->delete();
        return redirect()->back()->with('success', 'Registro eliminado.');
    }
}
