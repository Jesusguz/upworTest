<?php

namespace App\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskServiceInterface
{
    /**
     * Get all tasks based on the specified filter.
     *
     * @param string $filter 'all', 'pending', or 'completed'
     * @return Collection<int, Task>
     */
    public function getAllTasks(string $filter = 'all'): Collection;

    /**
     * Find a task by its ID.
     *
     * @param int $id
     * @return Task|null
     */
    public function findTaskById(int $id): ?Task;

    /**
     * Create a new task.
     *
     * @param array $data Task data, including 'title', 'description' (optional), 'category_id' (optional).
     * @return Task
     */
    public function createTask(array $data): Task;

    /**
     * Update an existing task.
     *
     * @param Task $task The task instance to update.
     * @param array $data Update data, including 'title', 'description' (optional), 'category_id' (optional).
     * @return bool True on success, false on failure.
     */
    public function updateTask(Task $task, array $data): bool;

    /**
     * Delete a task.
     *
     * @param Task $task The task instance to delete.
     * @return bool|null True on success, false on failure, null on error.
     */
    public function deleteTask(Task $task): ?bool;

    /**
     * Toggle the completion status of a task.
     *
     * @param Task $task The task instance to toggle.
     * @return bool The new completion status.
     */
    public function toggleTaskCompletion(Task $task): bool;

    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection; // Podrías tener un CategoryService aparte para SRP más estricto

}
