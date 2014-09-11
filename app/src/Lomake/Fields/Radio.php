<?php namespace App\Lomake\Fields;

class Radio extends Base
{
    protected $options = [
        'values'  => [],
        'default' => true,
        'options' => []
    ];

    public function render()
    {
        $params = $this->pick('name', 'values', 'default', 'options');
        if (empty($params['values'])) {
            $params['values'] = [
                trans('common.no'),
                trans('common.yes'),
            ];
        }
        return \View::make('varaa-lomake::fields.radio', $params)->render();
    }
}
