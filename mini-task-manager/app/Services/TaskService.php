<?php

namespace App\Services;

use App\Contracts\TaskServiceInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TaskService implements TaskServiceInterface
{
    /**
     * Get tasks based on a filter and order by position.
     *
     * @param string $filter 'all', 'pending', 'completed'.
     * @return Collection<int, Task>
     */
    public function getAllTasks(string $filter = 'all'): Collection
    {
        $query = Task::with('category');

        if ($filter === 'pending') {
            $query->where('is_completed', false);
        } elseif ($filter === 'completed') {
            $query->where('is_completed', true);
        }

        // Ordenar por la columna 'position' de forma ascendente
        // Las tareas sin posición (NULL) podrían aparecer al principio o al final dependiendo de la BD
        // Si quieres que las tareas sin posición aparezcan al final, puedes necesitar lógica adicional o un valor por defecto en la migración.
        return $query->orderBy('position', 'asc')->get();
    }

    /**
     * Find a task by its ID.
     *
     * @param int $id
     * @return Task|null
     */
    public function findTaskById(int $id): ?Task
    {
        return Task::find($id);
    }

    /**
     * Create a new task.
     *
     * @param array $data Task data (title, description, category_id, etc.).
     * @return Task
     * @throws \InvalidArgumentException
     */
    public function createTask(array $data): Task
    {
        // Validar datos mínimos
        $title = Arr::get($data, 'title');

        if (empty($title)) {
            throw new \InvalidArgumentException("Task title is required.");
        }

        // Preparar datos, asegurando que description y category_id son null si no se proveen
        $description = Arr::get($data, 'description', null);
        $categoryId = Arr::get($data, 'category_id', null);

        // Para la posición inicial, podrías asignar un valor alto
        // o dejarlo null y el drag/drop lo asignará.
        // Dejarlo null inicialmente es simple y el drag/drop lo gestionará.

        // Usar Eloquent para crear la tarea
        return Task::create([
            'title' => $title,
            'description' => $description,
            'category_id' => $categoryId,
            'is_completed' => false, // Nueva tarea no completada por defecto
            'position' => null, // Posición inicial null
        ]);
    }

    /**
     * Update an existing task.
     *
     * @param Task $task The task instance to update.
     * @param array $data Task data to update.
     * @return bool
     */
    public function updateTask(Task $task, array $data): bool
    {
        // Preparar datos para actualizar, manteniendo los valores actuales si no se proveen nuevos
        $title = Arr::get($data, 'title', $task->title);
        $description = Arr::get($data, 'description', $task->description);
        $categoryId = Arr::get($data, 'category_id', $task->category_id);
        // No actualizamos la posición aquí, eso se hace con updateTaskOrder

        // Usar Eloquent para actualizar la tarea
        return $task->update([
            'title' => $title,
            'description' => $description,
            'category_id' => $categoryId,
            // is_completed no se actualiza aquí, se hace con toggleTaskCompletion
            // position no se actualiza aquí, se hace con updateTaskOrder
        ]);
    }

    /**
     * Delete a task.
     *
     * @param Task $task The task instance to delete.
     * @return bool|null
     */
    public function deleteTask(Task $task): ?bool
    {
        return $task->delete();
    }

    /**
     * Toggle the completion status of a task.
     *
     * @param Task $task The task instance.
     * @return bool The new completion status.
     */
    public function toggleTaskCompletion(Task $task): bool
    {
        $task->is_completed = !$task->is_completed;
        $task->save();
        return $task->is_completed;
    }

    /**
     * Update the position of tasks based on a given order of IDs.
     *
     * @param array $taskIdsInOrder Array of task IDs in their desired order.
     * @return void
     */
    public function updateTaskOrder(array $taskIdsInOrder): void
    {
        // Usaremos una transacción
        DB::transaction(function () use ($taskIdsInOrder) {
            $position = 0;
            // Recorrer el array de IDs en el orden recibido
            foreach ($taskIdsInOrder as $taskId) {
                Task::where('id', $taskId)->update(['position' => $position]);
                $position++;
            }
        });
    }
}
