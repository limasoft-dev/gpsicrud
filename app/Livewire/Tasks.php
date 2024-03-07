<?php

namespace App\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Tasks extends Component
{
    // Traid to paginate the data
    use WithPagination;

    // Propertie to active only the tasks that are not completed
    public ?bool $checkCompleted = null;

    // Propertie to search tasks by name or description
    public ?string $search = '';

    // Propertie to sort the tasks by id, name, description or deadline
    public string $sortBy = 'id';

    // Propertie to define the sort direction ASC or DESC
    public string $sortDirection = 'ASC';

    // Properties to store the task data
    public string $name = '';
    public string $description = '';
    public string $deadline;
    public bool $completed = false;

    // Properties to control the modal to delete, add or edit a task
    public int $isDeletingTask = 0;
    public int $isShowingForm = 0;

    // Properties to store the task data to edit
    public ?Task $task = null;



    protected array $queryString = [
        'checkCompleted' => ['except' => null],
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortBy' => ['except' => 'id'],
        'sortDirection' => ['except' => 'ASC'],
    ];

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'required|min:3',
        'completed' => 'boolean',
        'deadline' => 'required|date',
    ];

    public function render(): View
    {
        $tasks = Task::where('user_id', auth()->id())
            ->when($this->search, function($query) {
                return $query->where(function($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->checkCompleted, function($query) {
                return $query->notcompleted();
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
        return view('livewire.tasks',[
            'tasks' => $tasks,
        ]);
    }

    public function updatingCompleted(): Void
    {
        $this->resetPage();
    }

    public function updatingSearch(): Void
    {
        $this->resetPage();
    }

    public function sorting(string $field): Void
    {
        if ($this->sortBy == $field) {
            $this->sortDirection = $this->sortDirection == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortDirection = 'ASC';
        }
        $this->sortBy = $field;
    }

    public function confirmTaskDeletion(int $id): Void
    {
        $this->isDeletingTask = $id;
    }

    public function confirmTaskAdding(): Void
    {
        $this->reset('name', 'description', 'deadline', 'completed','task');
        $this->isShowingForm = true;
    }

    public function confirmTaskEditing(Task $task): Void
    {
        $this->task = $task;
        $this->name = $task->name;
        $this->description = $task->description;
        $this->deadline = $task->deadline->format('Y-m-d');
        $this->completed = $task->completed;
        $this->isShowingForm = $task->id;
    }


    public function deleteTask(Task $task): Void
    {
        $task->delete();
        $this->isDeletingTask = false;
        session()->flash('message', 'Task deleted successfully.');
    }

    public function saveTask(): Void
    {
        $this->validate();

        if (isset ($this->task->id)) {
            $task = Task::find($this->isShowingForm);
            $task->update([
                'name' => $this->name,
                'description' => $this->description,
                'deadline' => $this->deadline,
                'completed' => $this->completed,
            ]);
            session()->flash('message', 'Task updated successfully.');
        } else {
            auth()->user()->tasks()->create([
                'name' => $this->name,
                'description' => $this->description,
                'deadline' => $this->deadline,
                'completed' => $this->completed,
            ]);
            session()->flash('message', 'Task created successfully.');
        }

        $this->isShowingForm = false;
        $this->resetPage();
    }





}
