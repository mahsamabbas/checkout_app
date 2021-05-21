<?php

namespace App\Http\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;

class Index extends Component
{
    public function delete($id)
    {
        if(Project::query()->findOrFail($id)->delete())
            session()->flash('success', 'Project successfully deleted');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('livewire.projects.index', [
            "projects" => Project::query()->paginate()
        ]);
    }
}