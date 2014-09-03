<?php namespace App\Lomake\Fields;

use DateTimeZone;

class TimezoneDropdown extends Dropdown
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->pick('name', 'values', 'default', 'options');
        $params['values'] = DateTimeZone::listIdentifiers();
        if ($this->options['key_is_value'] === true) {
            $params['values'] = $this->flipValues($params['values']);
        }
        return call_user_func_array('Form::select', $params);
    }
}
