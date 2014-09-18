<?php namespace App\Core\Fields;

use View;
use App\Lomake\Fields\Dropdown;
use App\Core\Models\BusinessCategory;

class BusinessCategoryDropdown extends Dropdown
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $all = BusinessCategory::getAll();
        return View::make('fields.business-category-dropdown', [
            'businessCategories' => $all,
            'item' => $this->model,
        ])->render();
    }
}
