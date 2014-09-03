<?php namespace App\Lomake\Fields;

class Factory
{
    public static function create($params)
    {
        $params = array_merge([
            'type' => 'text'
        ], $params);

        if (empty($params['name'])) {
            throw new \InvalidArgumentException('Missing name of the field');
        }

        $className = __NAMESPACE__.'\\'.studly_case($params['type']);
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Unsupported field type: '.$params['type']);
        }

        return new $className($params);
    }
}
