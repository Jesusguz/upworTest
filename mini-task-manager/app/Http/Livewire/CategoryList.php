<?php

namespace App\Http\Livewire;

use App\Contracts\CategoryServiceInterface;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class CategoryList extends Component
{
    public $newCategoryName = '';

    public Collection $categories;
    protected CategoryServiceInterface $categoryService;

    public function boot(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    // Reglas de validación
    protected $rules = [
        'newCategoryName' => 'required|string|max:255|unique:categories,name',
    ];


    // Método updated (debugging)
    public function updated($propertyName)
    {
        Log::info("CategoryList Property Updated: " . $propertyName);

        if ($propertyName === 'newCategoryName') {
            Log::info("newCategoryName updated. Value: " . $this->newCategoryName);
        }
    }

    public function mount()
    {
        $this->categories = new Collection();

        $this->loadCategories(); // Cargar categorías DESPUÉS de inicializar
        Log::info('CategoryList mounted.');
    }

    // Método render
    public function render()
    {
        return view('livewire.category-list');
    }

    // Método para cargar la lista de categorías
    public function loadCategories()
    {
        $this->categories = $this->categoryService->getAllCategories();
        Log::info('Categories loaded for CategoryList. Count: ' . $this->categories->count());
    }

    // Método para crear una nueva categoría
    public function createCategory()
    {

        $this->validate();

        try {
            $this->categoryService->createCategory(['name' => $this->newCategoryName]);
            $this->reset('newCategoryName');
            $this->loadCategories();

            $this->dispatch('toast', ['message' => 'Category created successfully!', 'type' => 'success']);
            Log::info('Category created successfully.');

            // $this->dispatch('categoryCreated');

        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            $this->dispatch('toast', ['message' => 'Error creating category: ' . $e->getMessage(), 'type' => 'error']);
        }
    }
}
