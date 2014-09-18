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
        ];

        if (empty($params['values'])) {
            $params['values'] = [
                trans('common.no'),
                trans('common.yes'),
            ];
        }

        return \View::make('varaa-lomake::fields.radio', $params)->render();
    }
}
