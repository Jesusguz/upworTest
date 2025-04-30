<?php

namespace App\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    /**
     *
     *
     * @return Collection<int, Category>
     */
    public function getAllCategories(): Collection;

    /**
     *
     *
     * @param int $id
     * @return Category|null
     */
    public function findCategoryById(int $id): ?Category;


    /**
     * Create a new category.
     *
     * @param array $data Category data, including 'name' and optionally 'color'.
     * @return Category
     * @throws \InvalidArgumentException
     */
    public function createCategory(array $data): Category;
    // public function updateCategory(Category $category, array $data): bool;
    // public function deleteCategory(Category $category): ?bool;
}
