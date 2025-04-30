<?php

namespace App\Services;

use App\Contracts\CategoryServiceInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class CategoryService implements CategoryServiceInterface
{
    /**
     * Get all categories.
     *
     * @return Collection<int, Category>
     */
    public function getAllCategories(): Collection
    {
        return Category::all();
    }

    /**
     * Find a category by its ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findCategoryById(int $id): ?Category
    {
        return Category::find($id);
    }
    protected function generateRandomHexColor(): string
    {
        // Genera un número entero aleatorio entre 0 y 16777215 (0xFFFFFF)
        $randomInt = mt_rand(0, 0xFFFFFF);
        $hexColor = str_pad(dechex($randomInt), 6, '0', STR_PAD_LEFT);

        return '#' . $hexColor; // Devuelve el código de color con el prefijo #
    }
    /**
     * Create a new category.
     *
     * @param array $data Category data, including 'name' and optionally 'color'.
     * @return Category
     * @throws \InvalidArgumentException
     */

    public function createCategory(array $data): Category
    {
        $name = Arr::get($data, 'name');

        if (empty($name)) {
            throw new \InvalidArgumentException("Category name is required.");
        }
        $color = Arr::get($data, 'color');
        if (empty($color)) {
            $color = $this->generateRandomHexColor();
        }
        return Category::create([
            'name' => $name,
            'color' => $color,
        ]);
    }

}
