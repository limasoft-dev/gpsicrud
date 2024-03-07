<div class="p-6 lg:p-8 bg-white border-b border-gray-200">

    <h1 class="mt-8 text-2xl font-medium text-gray-900">
        Tasks
    </h1>

    <div class="mt-6">
        <div class="flex justify-between">
            <div>
                <input type="search" placeholder="Search" class=" shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model.live='search'>
            </div>
            <dir class=" mr-2">
                <input type="checkbox" class=" mr-2 leading-tight" wire:model.live='completed'> Not completed only?
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
                    @if (!$completed)
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
                    @if (!$completed)
                        <td class="border px-4 py-2">{{ $task->completed ? 'Yes' : 'No' }}</td>
                    @endif
                    <td class="border px-4 py-2">
                        Edit Delete
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $tasks->links()}}
    </div>
</div>
