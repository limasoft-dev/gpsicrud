<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg wire:click="$set('isShowingForm', false)" class="fill-current h-6 w-6 text-green-500" role="button"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1 1 0 0 1-1.497-1.32l.083-.094L16.585 10l-3.15-3.435a1 1 0 1 1 1.414-1.415l3.5 3.5a1 1 0 0 1 0 1.414l-3.5 3.5a1 1 0 0 1-.708.293z" />
                    <path
                        d="M5.652 14.849a1 1 0 0 0 1.497-1.32l-.083-.094L3.415 10l3.15-3.435a1 1 0 0 0-1.414-1.415l-3.5 3.5a1 1 0 0 0 0 1.414l3.5 3.5a1 1 0 0 0 .708.293z" />
                </svg>
            </span>
        </div>
    @endif
    <div class="mt-8 text-2xl flex justify-between">
        <div>Tasks</div>
        <div class="mr-2">
            <x-button wire:click="confirmTaskAdding" class="bg-blue-500 hover:bg-blue-700">
                {{ __('Add New Task') }}
            </x-button>
        </div>
    </div>


    <div class="mt-6">
        <div class="flex justify-between">
            <div>
                <input type="search" placeholder="Search"
                    class=" shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    wire:model.live='search'>
            </div>
            <dir class=" mr-2">
                <input type="checkbox" class=" mr-2 leading-tight" wire:model.live='checkCompleted'> Not completed only?
            </dir>
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sorting('id')">#</button>
                            <x-sort-icon :sortBy="$sortBy" :sortDirection="$sortDirection" field="id" />
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sorting('name')">Name</button>
                            <x-sort-icon :sortBy="$sortBy" :sortDirection="$sortDirection" field="name" />
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sorting('deadline')">Deadline</button>
                            <x-sort-icon :sortBy="$sortBy" :sortDirection="$sortDirection" field="deadline" />
                        </div>
                    </th>
                    @if (!$checkCompleted)
                    <th class="px-4 py-2">
                        Completed
                    </th>
                    @endif
                    <th class="px-4 py-2">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td class="border px-4 py-2">{{ $task->id }}</td>
                    <td class="border px-4 py-2">{{ $task->name }}</td>
                    <td class="border px-4 py-2">{{ date_format($task->deadline,'d/m/Y') }}</td>
                    @if (!$checkCompleted)
                    <td class="border px-4 py-2">{{ $task->completed ? 'Yes' : 'No' }}</td>
                    @endif
                    <td class="border px-4 py-2">
                        <x-button wire:click="confirmTaskEditing({{$task->id}})"
                            class="bg-yellow-500 hover:bg-yellow-700">
                            {{ __('Edit') }}
                        </x-button>
                        <x-danger-button wire:click="confirmTaskDeletion({{$task->id}})" wire:loading.attr="disabled">
                            {{ __('Delete') }}
                        </x-danger-button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $tasks->links()}}
    </div>

    {{-- Modal to Confirm Task Delete --}}
    <x-dialog-modal wire:model.live="isDeletingTask" id="delete">
        <x-slot name="title">
            {{ __('Delete Task') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete your task? ') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isDeletingTask', 0)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteTask({{$isDeletingTask}})" wire:loading.attr="disabled">
                {{ __('Delete Task') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Modal to Confirm Task Add --}}
    <x-dialog-modal wire:model.live="isShowingForm" id="add">
        <x-slot name="title">
            {{ isset($this->task->id) ? __('Edit Task') : __('Add New Task') }}
        </x-slot>

        <x-slot name="content">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model.live="name" required />
                <x-input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-label for="description" value="{{ __('description') }}" />
                <textarea id="description" cols="4" class="mt-1 block w-full rounded-lg border-gray-300"
                    wire:model.live="description" required></textarea>
                <x-input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-label for="deadline" value="{{ __('Deadline') }}" />
                <input type="date" id="deadline" class="mt-1 block w-full rounded-lg border-gray-300" wire:model.live='deadline' required>
                <x-input-error for="deadline" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mt-4">
                <label class="flex items-center">
                    <input id="completed" type="checkbox" wire:model.live="completed"
                        class="form-checkbox rounded border-gray-300" />
                    <span class="ml-2 text-sm text-gray-600">Completed</span>
                </label>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isShowingForm', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="saveTask" wire:loading.attr="disabled"
                class="bg-blue-500 hover:bg-blue-700">
                {{ isset($this->task->id) ? __('Update Task') : __('Add Task') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
