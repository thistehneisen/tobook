<?php namespace App\Lomake\Fields;

use DateTimeZone;

class TimezoneDropdown extends Dropdown
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->getParams();
        $params['values'] = DateTimeZone::listIdentifiers();
        if ($this->options['flipValues'] === true) {
            $params['values'] = $this->flipValues($params['values']);
        }
        return call_user_func_array('Form::select', $params);
    }
}
