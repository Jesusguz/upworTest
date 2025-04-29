<?php

namespace App\Http\Livewire;

use App\Models\Task; // Still needed for type-hinting
use App\Contracts\TaskServiceInterface; // Import the interface
use Livewire\Component;
use Livewire\Attributes\On; // For events, optional depending on Livewire version
use Livewire\Attributes\Layout;
#[Layout('layouts.app')] // <-- Add this line, specify the view name
class TaskList extends Component
{
    // Propiedades de estado del componente y campos del formulario
    public $tasks;
    public $filter = 'all';
    public $showCreateForm = false;
    public $newTaskTitle = '';
    public $newTaskDescription = '';
    public $newTaskCategory = null;

    // Propiedades para el modal de detalle o edición
    public $selectedTask = null;
    public $showTaskDetailModal = false;
    public $editingTaskId = null;
    public $editingTaskTitle = '';
    public $editingTaskDescription = '';
    public $editingTaskCategory = null;

    // Propiedad para la confirmación de eliminación
    public $taskToDeleteId = null;
    public $showDeleteConfirmationModal = false;

    // --- CORRECCIÓN AQUÍ ---
    // Declara la propiedad para el servicio sin inicializarla en el constructor
    protected TaskServiceInterface $taskService;

    // Inyecta la dependencia en el método boot()
    public function boot(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }
    // --- FIN CORRECCIÓN ---


    // Validation rules for task creation and update
    protected $rules = [
        'newTaskTitle' => 'required|string|max:255',
        'newTaskDescription' => 'nullable|string',
        'newTaskCategory' => 'nullable|exists:categories,id',
        'editingTaskTitle' => 'required|string|max:255',
        'editingTaskDescription' => 'nullable|string',
        'editingTaskCategory' => 'nullable|exists:categories,id',
    ];

    // Mount method to load initial data (now uses the injected service)
    public function mount()
    {
        $this->loadTasks();
    }

    // Method to load tasks based on filter, delegating to the service
    public function loadTasks()
    {
        // Use the service property initialized in boot()
        $this->tasks = $this->taskService->getAllTasks($this->filter);
    }

    // Method to set the filter and reload tasks
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadTasks();
    }

    // Method to create a new task, delegating to the service after validation
    public function createTask()
    {
        $this->validate([
            'newTaskTitle' => 'required|string|max:255',
            'newTaskDescription' => 'nullable|string',
            'newTaskCategory' => 'nullable|exists:categories,id',
        ]);

        // Use the service property
        $this->taskService->createTask([
            'title' => $this->newTaskTitle,
            'description' => $this->newTaskDescription,
            'category_id' => $this->newTaskCategory,
        ]);

        // Reset form fields and hide the form
        $this->reset(['newTaskTitle', 'newTaskDescription', 'newTaskCategory', 'showCreateForm']);
        $this->loadTasks(); // Reload the list to show the new task
        $this->dispatch('toast', ['message' => 'Task created successfully!', 'type' => 'success']);
    }

    // Method to toggle task completion, delegating to the service
    // Livewire can resolve the Task model automatically if passed by ID from the view
    public function toggleCompleted(Task $task)
    {
        // Use the service property
        $this->taskService->toggleTaskCompletion($task);
        $this->loadTasks(); // Reload to update visual state, especially if filtering
        $this->dispatch('toast', ['message' => 'Task status updated!', 'type' => 'info']);
    }

    // Methods for the detail/edit modal
    public function showTaskDetail(Task $task)
    {
        // Use the service to find the task if needed, or rely on Livewire model binding
        $this->selectedTask = $task;
        $this->editingTaskId = $task->id;
        $this->editingTaskTitle = $task->title;
        $this->editingTaskDescription = $task->description;
        $this->editingTaskCategory = $task->category_id;
        $this->showTaskDetailModal = true;
    }

    public function closeTaskDetail()
    {
        $this->reset(['selectedTask', 'showTaskDetailModal', 'editingTaskId', 'editingTaskTitle', 'editingTaskDescription', 'editingTaskCategory']);
    }

    public function updateTask()
    {
        $this->validate([
            'editingTaskTitle' => 'required|string|max:255',
            'editingTaskDescription' => 'nullable|string',
            'editingTaskCategory' => 'nullable|exists:categories,id',
        ]);

        // Use the service property to find and update the task
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
        }
    }

    // Methods for delete confirmation
    public function confirmDelete($taskId)
    {
        $this->taskToDeleteId = $taskId;
        $this->showDeleteConfirmationModal = true;
    }

    public function cancelDelete()
    {
        $this->reset(['taskToDeleteId', 'showDeleteConfirmationModal']);
    }

    public function deleteTask()
    {
        // Use the service property to find and delete the task
        $task = $this->taskService->findTaskById($this->taskToDeleteId);

        if ($task) {
            $this->taskService->deleteTask($task);
            $this->loadTasks();
            $this->dispatch('toast', ['message' => 'Task deleted successfully!', 'type' => 'success']);
        }
        $this->reset(['taskToDeleteId', 'showDeleteConfirmationModal']);
    }


    // Render method to display the view
    public function render()
    {
        // Get categories through the service property
        $categories = $this->taskService->getAllCategories();

        return view('livewire.task-list', [
            'categories' => $categories,
        ]);
    }
}
