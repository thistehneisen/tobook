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

        // If we're provided a full classname
        $className = __NAMESPACE__.'\\'.studly_case($params['type']);
        if (strpos($params['type'], '\\') !== false) {
            $className = $params['type'];
        }

        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Unsupported field type: '.$className);
        }

        return new $className($params);
    }
}
