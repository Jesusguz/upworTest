<?php

namespace App\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskServiceInterface
{
    /**
     * Get tasks based on a filter and order by position.
     *
     * @param string $filter 'all', 'pending', 'completed'.
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
     * @param array $data Task data (title, description, category_id, etc.).
     * @return Task
     * @throws \InvalidArgumentException
     */
    public function createTask(array $data): Task;

    /**
     * Update an existing task.
     *
     * @param Task $task The task instance to update.
     * @param array $data Task data to update.
     * @return bool
     */
    public function updateTask(Task $task, array $data): bool;

    /**
     * Delete a task.
     *
     * @param Task $task The task instance to delete.
     * @return bool|null
     */
    public function deleteTask(Task $task): ?bool;

    /**
     * Toggle the completion status of a task.
     *
     * @param Task $task The task instance.
     * @return bool The new completion status.
     */
    public function toggleTaskCompletion(Task $task): bool;

    /**
     * Update the position of tasks based on a given order of IDs.
     *
     * @param array $taskIdsInOrder Array of task IDs in their desired order.
     * @return void
     */
    public function updateTaskOrder(array $taskIdsInOrder): void;
}
