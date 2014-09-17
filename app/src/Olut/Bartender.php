<?php namespace App\Olut;

class Bartender
{
    /**
     * A list of custom presenter attach to a field
     *
     * @var array
     */
    protected $presenters = [];

    public function __construct($presenters)
    {
        $this->setPresenters($presenters);
    }

    public function setPresenters($value)
    {
        $this->presenters = $value;
    }

    public function getPresenters()
    {
        return $this->presenters;
    }

    public function mix($field, $item)
    {
        $presenterClass = !empty($this->presenters[$field])
            ? $this->presenters[$field]
            : $this->getDefaultPresenter($field);

        $presenter = new $presenterClass;
        return $presenter->render($item->$field);
    }

    /**
     * Determine which default presenter we should use
     *
     * @param string $field Use to guess the type
     *
     * @return string
     */
    protected function getDefaultPresenter($field)
    {
        if (starts_with($field, 'is_')) {
            return 'App\Olut\Presenters\Bool';
        }

        switch ($field) {
            case 'email':
                return 'App\Olut\Presenters\Email';
            default:
                return 'App\Olut\Presenters\Base';
        }
    }
}
