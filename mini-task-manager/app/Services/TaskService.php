<?php

namespace App\Services;

use App\Contracts\TaskServiceInterface;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr; // Utilidad para arrays

class TaskService implements TaskServiceInterface
{
    public function getAllTasks(string $filter = 'all'): Collection
    {
        $query = Task::with('category'); // Eager load the category relationship

        if ($filter === 'pending') {
            $query->where('is_completed', false);
        } elseif ($filter === 'completed') {
            $query->where('is_completed', true);
        }

        return $query->latest()->get(); // Order by latest creation date
    }

    public function findTaskById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function createTask(array $data): Task
    {
        // Clean and prepare data before creating
        $data['description'] = Arr::get($data, 'description', null); // Set description to null if not provided
        $data['category_id'] = Arr::get($data, 'category_id', null); // Set category_id to null if not provided

        // Use Eloquent to create the task
        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
        ]);
    }

    public function updateTask(Task $task, array $data): bool
    {
        // Clean and prepare data before updating
        $data['description'] = Arr::get($data, 'description', $task->description); // Keep current if not provided
        $data['category_id'] = Arr::get($data, 'category_id', $task->category_id); // Keep current if not provided


        // Use Eloquent to update the task
        return $task->update([
            'title' => Arr::get($data, 'title', $task->title), // Keep current if not provided
            'description' => $data['description'],
            'category_id' => $data['category_id'],
        ]);
    }

    public function deleteTask(Task $task): ?bool
    {
        return $task->delete();
    }

    public function toggleTaskCompletion(Task $task): bool
    {
        $task->is_completed = !$task->is_completed;
        $task->save();
        return $task->is_completed; // Return the new completion status
    }

    public function getAllCategories(): Collection
    {
        return Category::all(); // Interacting with Category model here
    }
}
