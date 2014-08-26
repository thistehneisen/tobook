<?php namespace App\Lomake;

use App, View;

class Lomake
{
    /**
     * Make the form based on passed model
     *
     * @param Illuminate\Database\Eloquent\Model|string $model name of the class
     *                                                         or an instance
     * @param array                                     $opt
     *
     * @return View
     */
    public function make($model, $opt = [])
    {
        if (is_string($model)) {
            $model = App::make($model);
        }

        // Now we have an object
        $instance = $model;

        $opt = array_merge([
            'form' => ['class' => 'form-horizontal well']
        ], $opt);

        $fields = [];
        foreach ($instance->fillable as $name) {
            // If we have $opt['trans'], we will prepend it to the name
            // Values of $opt['trans'] could be [consumer].name, [user.form].name
            $field['label'] = isset($opt['trans'])
                ? trans($opt['trans'].'.'.$name)
                : $name;

            // Try to guess the type of this field
            $field['type'] = $this->guessInputType($name);

            $fields[$name] = $field;
        }

        return View::make('varaa-lomake::form', [
            'fields' => $fields,
            'opt'    => $opt
        ]);
    }

    /**
     * Guess the input type for this field based on the name
     *
     * @param string $name
     *
     * @return string
     */
    protected function guessInputType($name)
    {
        if (starts_with($name, 'password')) {
            return 'password';
        }

        if (starts_with($name, 'is_')) {
            return 'radio';
        }

        return 'text';
    }
}
