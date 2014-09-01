<?php namespace App\Lomake\Fields;

use Carbon\Carbon;

/**
 * Label of options in dropdown will show current date time in format
 */
class DateTimeDropdown extends Dropdown
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->pick('name', 'values', 'default', 'options');

        if ($this->options['key_is_value'] === true) {
            $params['values'] = $this->flipValues($params['values']);
        }

        // Print the current date as label
        $params['values'] = $this->makeLabels($params['values']);

        return call_user_func_array('Form::select', $params);
    }

    /**
     * Print current date as values in dropdown list
     *
     * @param array $arr
     *
     * @return array
     */
    protected function makeLabels($arr)
    {
        $ret = [];
        $now = Carbon::now();
        foreach ($arr as $key => $value) {
            $ret[$key] = $now->format($value);
        }

        return $ret;
    }
}
