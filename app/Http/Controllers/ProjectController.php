<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Response;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;

class ProjectController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        $projects = Project::all();

        return view('project.index', compact('projects'));
    }

    /**
     * @return Response
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * @param ProjectStoreRequest $request
     * @return Response
     */
    public function store(ProjectStoreRequest $request)
    {
        Project::create($request->all());

        session()->flash('success', 'Project successfully created');

        return redirect()->route('projects.index');
    }


    /**
     * @param Project $project
     * @return Response
     */
    public function edit(Project $project)
    {
        return view('project.edit', compact('project'));
    }

    /**
     * @param ProjectUpdateRequest $request
     * @param Project $project
     * @return Response
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->update($request->only($project->getFillable()));

        session()->flash('success', 'Project successfully updated');

        return redirect()->route('projects.index');
    }
}
