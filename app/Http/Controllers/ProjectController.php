<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['tasks', 'creator'])->latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'required|string|size:7',
        ]);
        $data['created_by'] = Auth::id();
        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Proyecto creado.');
    }

    public function show(Project $project)
    {
        $project->load(['tasks.assignee', 'tasks.timeLogs', 'creator']);
        $tasksByStatus = $project->tasks->groupBy('status');
        return view('projects.show', compact('project', 'tasksByStatus'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'required|string|size:7',
        ]);
        $project->update($data);

        return redirect()->route('projects.show', $project)->with('success', 'Proyecto actualizado.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado.');
    }
}
