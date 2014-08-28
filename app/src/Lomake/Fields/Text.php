<?php namespace App\Lomake\Fields;

class Text extends Base
{
    protected $options = [
        'values'  => '',
        'options' => []
    ];

    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->pick('name', 'values', 'options');
        return call_user_func_array('Form::text', $params);
    }
}
