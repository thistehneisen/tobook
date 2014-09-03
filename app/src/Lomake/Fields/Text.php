<?php namespace App\Lomake\Fields;

class Text extends Base
{
    protected $options = [
        'values'  => '',
        'options' => ['class' => 'form-control input-sm']
    ];

    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->pick('name', 'values', 'options');
        if (empty($params['values'])) {
            $params['values'] = $this->options['default'];
        }

        return call_user_func_array('Form::text', $params);
    }
}
