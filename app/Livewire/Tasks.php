<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Tasks extends Component
{
    use WithPagination;
    public ?bool $completed = null;
    public ?string $search = '';

    public string $sortBy = 'id';
    public string $sortDirection = 'ASC';

    protected array $queryString = [
        'completed' => ['except' => null],
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortBy' => ['except' => 'id'],
        'sortDirection' => ['except' => 'ASC'],
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
            ->when($this->completed, function($query) {
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
}
