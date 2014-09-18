<?php namespace App\Lomake\Fields;

class Dropdown extends Base
{
    protected $opt = [
        'options'    => ['class' => 'form-control'],
        'flipValues' => true
    ];

    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        if (empty($this->values)) {
            throw new \InvalidArgumentException('You must provide `values` to use Dropdown');
        }

        $params = $this->getParams();
        if ($this->opt['flipValues'] === true) {
            $params['values'] = $this->flipValues($params['values']);
        }
        return call_user_func_array('Form::select', $params);
    }

    /**
     * Get params to be passed to view
     *
     * @return array
     */
    protected function getParams()
    {
        return [
            'name'    => $this->name,
            'values'  => $this->values,
            'default' => $this->default,
            'options' => $this->options,
        ];
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
