<?php namespace App\Lomake\Fields;

class Radio extends Base
{
    public function render()
    {
        $params = [
            'name'    => $this->name,
            'values'  => $this->values,
            'default' => $this->default,
            'options' => $this->options,
            'model'   => $this->model,
        ];

        if (empty($params['values'])) {
            $params['values'] = [
                0 => trans('common.no'),
                1 => trans('common.yes'),
            ];
        }

        return \View::make('varaa-lomake::fields.radio', $params)->render();
    }
}
