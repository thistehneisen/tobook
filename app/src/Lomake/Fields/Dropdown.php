<?php namespace App\Lomake\Fields;

class Dropdown extends Base
{
    protected $options = [
        'values'       => [],
        'default'      => '',
        'options'      => ['class' => 'form-control'],
        'key_is_value' => true
    ];

    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->pick('name', 'values', 'default', 'options');
        // dd($params);
        if ($this->options['key_is_value'] === true) {
            $params['values'] = $this->flipValues($params['values']);
        }
        return call_user_func_array('Form::select', $params);
    }

    /**
     * Receive a number-indexed array and convert to a text-indexed array
     *
     * @param array $arr
     *
     * @return array
     */
    protected function flipValues($arr)
    {
        $ret = [];
        foreach ($arr as $value) {
            $ret[$value] = $value;
        }
        return $ret;
    }
}
