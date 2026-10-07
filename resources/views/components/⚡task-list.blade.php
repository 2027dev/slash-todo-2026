<?php

use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $title = '';

    #[Computed]
    public function tasks()
    {
        return Task::orderBy('completed')->orderBy('created_at')->get();
    }

    public function addTask(): void
    {
        $validated = trim($this->title);

        if ($validated === '') {
            return;
        }

        Task::create(['title' => $validated]);

        $this->title = '';

        unset($this->tasks);
    }

    public function toggleTask(Task $task): void
    {
        $task->update(['completed' => ! $task->completed]);

        unset($this->tasks);
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();

        unset($this->tasks);
    }
};
?>

<div class="mx-auto mt-24 w-full max-w-md px-6">
    <p class="mb-8 text-sm font-medium text-zinc-500">{{ now()->format('l') }}</p>

    <ul class="mb-6 space-y-5">
        @forelse ($this->tasks as $task)
            <li wire:key="task-{{ $task->id }}" class="group flex items-center justify-between gap-3">
                <button
                    type="button"
                    wire:click="toggleTask({{ $task->id }})"
                    class="text-left text-lg font-semibold tracking-tight transition {{ $task->completed ? 'text-zinc-600 line-through' : 'text-white' }}"
                >
                    {{ $task->title }}
                </button>

                <button
                    type="button"
                    wire:click="deleteTask({{ $task->id }})"
                    class="shrink-0 text-zinc-600 opacity-0 transition hover:text-zinc-300 group-hover:opacity-100"
                    aria-label="Delete task"
                >
                    &times;
                </button>
            </li>
        @empty
            <li class="text-lg font-semibold text-zinc-600">Nothing here yet.</li>
        @endforelse
    </ul>

    <form wire:submit="addTask">
        <input
            type="text"
            wire:model="title"
            placeholder="Add task..."
            autofocus
            class="w-full border-0 border-b border-zinc-700 bg-transparent pb-3 text-lg font-medium text-zinc-400 placeholder-zinc-500 caret-violet-500 outline-none focus:border-violet-500 focus:ring-0 focus:outline-none"
        >
    </form>

    <button
        type="button"
        wire:click="addTask"
        class="mt-8 w-full rounded-lg bg-violet-600 py-4 text-base font-semibold text-white transition hover:bg-violet-500 active:bg-violet-700"
    >
        Start Slashing
    </button>
</div>
