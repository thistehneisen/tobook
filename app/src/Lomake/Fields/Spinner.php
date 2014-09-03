<?php namespace App\Lomake\Fields;

class Spinner extends Text
{
    protected $options = [
        'values'  => '',
        'options' => ['class' => 'form-control input-sm spinner']
    ];

    public function render()
    {
        $params = $this->pick('name', 'values', 'options');
        $input = call_user_func_array('Form::text', $params);

        $options = [];
        foreach ($params['options'] as $key => $value) {
            if (starts_with($key, 'data-')) {
                $options[] = "$key=\"$value\"";
            }
        }
        $options = implode(' ', $options);

        return \View::make('varaa-lomake::fields.spinner', [
            'input'   => $input,
            'options' => $options
        ])->render();
    }
}
