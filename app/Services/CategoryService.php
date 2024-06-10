<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;

class CategoryService
{
    public function createCategoriesAndSubcategories(array $categoriesWithSubcategories, User $user): void
    {
        foreach ($categoriesWithSubcategories as $categoryName => $subcategories) {
            $category = Category::firstOrCreate([
                'name' => $categoryName,
                'user_id' => $user->id
            ]);

            foreach ($subcategories as $subcategoryName) {
                Subcategory::firstOrCreate([
                    'name' => $subcategoryName,
                    'category_id' => $category->id,
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
