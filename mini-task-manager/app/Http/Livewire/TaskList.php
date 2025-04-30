<?php

namespace App\Http\Livewire;

use App\Models\Task;
use App\Contracts\TaskServiceInterface;
use App\Contracts\CategoryServiceInterface;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class TaskList extends Component
{
    public $tasks;
    public $filter = 'all';
    public $showCreateForm = true;
    public $newTaskTitle = '';
    public $newTaskDescription = '';
    public $newTaskCategory = null;

    public $selectedTask = null;
    public $showTaskDetailModal = false;

    public $editingTaskId = null;
    public $editingTaskTitle = '';
    public $editingTaskDescription = '';
    public $editingTaskCategory = null;

    public $taskToDeleteId = null;
    public $showDeleteConfirmationModal = false;

    public Collection $categoriesForSelectors;

    protected TaskServiceInterface $taskService;
    protected CategoryServiceInterface $categoryService;

    public function boot(TaskServiceInterface $taskService, CategoryServiceInterface $categoryService)
    {
        $this->taskService = $taskService;
        $this->categoryService = $categoryService;
    }

    protected $rules = [
        'newTaskTitle' => 'required|string|max:255',
        'newTaskDescription' => 'nullable|string',
        'newTaskCategory' => 'nullable|exists:categories,id',

        'editingTaskTitle' => 'required|string|max:255',
        'editingTaskDescription' => 'nullable|string',
        'editingTaskCategory' => 'nullable|exists:categories,id',
    ];

    public function updated($propertyName)
    {
        // You can add specific updated logic here if needed
    }

    public function mount()
    {
        $this->categoriesForSelectors = new Collection();
        $this->loadTasks();
        $this->resetEditingState();
        $this->loadCategoriesForSelectors();
        Log::info('TaskList mounted.');
    }

    public function render()
    {
        return view('livewire.task-list', [
            'categories' => $this->categoriesForSelectors,
        ]);
    }

    protected function loadCategoriesForSelectors()
    {
        $this->categoriesForSelectors = $this->categoryService->getAllCategories();
    }

    public function loadTasks()
    {
        $this->tasks = $this->taskService->getAllTasks($this->filter);
        Log::info('Tasks loaded. Filter: ' . $this->filter . ', Count: ' . ($this->tasks ? $this->tasks->count() : 'null'));
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadTasks();
        $this->resetEditingState();
        Log::info('Filter set to: ' . $filter);
    }

    public function createTask()
    {
        $this->validate(['newTaskTitle' => 'required|string|max:255']);
        $this->taskService->createTask(['title' => $this->newTaskTitle, 'description' => null, 'category_id' => null]);
        $this->reset('newTaskTitle');
        $this->loadTasks();
        $this->dispatch('toast', ['message' => 'Task created successfully!', 'type' => 'success']);
        Log::info('Task created successfully.');
    }

    public function toggleCompleted(Task $task)
    {
        $this->taskService->toggleTaskCompletion($task);
        $this->loadTasks();
        $this->dispatch('toast', ['message' => 'Task status updated!', 'type' => 'info']);
        Log::info('Task status updated via toggle.');
    }

    public function showTaskDetail(Task $task)
    {
        $this->resetEditingState();
        $this->selectedTask = $task;
        $this->editingTaskId = $task->id;
        $this->editingTaskTitle = $task->title;
        $this->editingTaskDescription = $task->description ?? '';
        $this->editingTaskCategory = $task->category_id;
        $this->showTaskDetailModal = true;
        Log::info('Task detail modal shown for task: ' . $task->id);
    }

    public function closeTaskDetail()
    {
        $this->resetEditingState();
        $this->showTaskDetailModal = false;
        Log::info('Task detail modal closed.');
    }

    public function updateTask()
    {
        if ($this->editingTaskId === null) {
            $this->dispatch('toast', ['message' => 'Error: No task selected for update.', 'type' => 'error']);
            return;
        }

        $this->validate([
            'editingTaskTitle' => 'required|string|max:255',
            'editingTaskDescription' => 'nullable|string',
            'editingTaskCategory' => 'nullable|exists:categories,id',
        ]);

        $task = $this->taskService->findTaskById($this->editingTaskId);

        if ($task) {
            $this->taskService->updateTask($task, [
                'title' => $this->editingTaskTitle,
                'description' => $this->editingTaskDescription,
                'category_id' => $this->editingTaskCategory,
            ]);
            $this->loadTasks();
            $this->closeTaskDetail();
            $this->dispatch('toast', ['message' => 'Task updated successfully!', 'type' => 'success']);
            Log::info('Task updated successfully: ' . $task->id);
        } else {
            $this->dispatch('toast', ['message' => 'Error: Task not found.', 'type' => 'error']);
            $this->closeTaskDetail();
            Log::error('Task not found for update: ' . $this->editingTaskId);
        }
    }

    public function confirmDelete($taskId)
    {
        $this->taskToDeleteId = $taskId;
        $this->showDeleteConfirmationModal = true;
        $this->resetEditingState();
        $this->showTaskDetailModal = false;
        Log::info('Delete confirmation modal shown.');
    }

    public function cancelDelete()
    {
        $this->reset(['taskToDeleteId', 'showDeleteConfirmationModal']);
        $this->resetEditingState();
        Log::info('Delete cancelled.');
    }

    public function deleteTask()
    {
        if ($this->taskToDeleteId === null) {
            $this->dispatch('toast', ['message' => 'Error: No task selected for deletion.', 'type' => 'error']);
            return;
        }

        $task = $this->taskService->findTaskById($this->taskToDeleteId);

        if ($task) {
            $this->taskService->deleteTask($task);
            $this->loadTasks();
            $this->dispatch('toast', ['message' => 'Task deleted successfully!', 'type' => 'success']);
            Log::info('Task deleted successfully: ' . $task->id);
        } else {
            $this->dispatch('toast', ['message' => 'Error: Task not found.', 'type' => 'error']);
            Log::error('Task not found for deletion: ' . $this->taskToDeleteId);
        }
        $this->reset(['taskToDeleteId', 'showDeleteConfirmationModal']);
        $this->resetEditingState();
        Log::info('Delete task method finished.');
    }

    protected function resetEditingState()
    {
        $this->reset([
            'selectedTask',
            'editingTaskId',
            'editingTaskTitle',
            'editingTaskDescription',
            'editingTaskCategory',
        ]);
    }

    // --- Cleaned reorderTasks method (Receive UNTYPED Parameter, Manual JSON DECODING) ---
    #[On('task-reordered')]
    public function reorderTasks(array $order)
    {
        try {
            // Validar que el array no esté vacío
            if (empty($order)) {
                throw new \Exception("Empty task order array received");
            }

            // Convertir todos los IDs a enteros
            $order = array_map('intval', $order);

            Log::info('Received task order:', ['order' => $order]);

            // Llamar al servicio para actualizar el orden
            $this->taskService->updateTaskOrder($order);

            // Actualizar la lista de tareas
            $this->loadTasks();

            $this->dispatch('toast', [
                'message' => 'Task order updated successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Error reordering tasks: ' . $e->getMessage(), [
                'exception' => $e,
                'received_order' => $order ?? null
            ]);

            $this->dispatch('toast', [
                'message' => 'Error updating task order: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
    public function exportPdf()
    {
        $tasksToExport = $this->taskService->getAllTasks($this->filter);
        $html = view('livewire.task-pdf', ['tasks' => $tasksToExport, 'filter' => $this->filter])->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = 'tasks_' . Str::slug($this->filter) . '_' . now()->format('Ymd_His') . '.pdf';

        return Response::streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $fileName, ['Content-Type' => 'application/pdf']);
    }

    public function exportCsv()
    {
        $tasksToExport = $this->taskService->getAllTasks($this->filter);
        $fileName = 'tasks_' . Str::slug($this->filter) . '_' . now()->format('Ymd_His') . '.csv';

        return Response::streamDownload(function () use ($tasksToExport) {
            $file = fopen('php://temp', 'w+');
            fputcsv($file, ['Title', 'Description', 'Status', 'Category']);

            foreach ($tasksToExport as $task) {
                fputcsv($file, [
                    $task->title,
                    $task->description ?? '',
                    $task->is_completed ? 'Completed' : 'Pending',
                    $task->category ? $task->category->name : 'No Category',
                ]);
            }

            rewind($file);
            $output = stream_get_contents($file);
            fclose($file);
            echo $output;
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
