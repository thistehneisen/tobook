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

        return <<< HTML
            <div class="input-group input-group-sm spinner" {$options}>
                {$input}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
HTML;
    }
}
