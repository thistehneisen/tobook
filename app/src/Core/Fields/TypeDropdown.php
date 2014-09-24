<?php namespace App\Core\Fields;

use App\Lomake\Fields\Dropdown;
use Form;

class TypeDropdown extends Dropdown
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        return Form::select('type', ['Percent' => trans('loyalty-card.percent'), 'Cash' => trans('loyalty-card.cash')], $this->model->type, ['class' => 'form-control']);
    }
}
