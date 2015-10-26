<?php namespace App\Core\Controllers;

use Confide, View;
use Illuminate\Support\MessageBag, Settings;

class Base extends \Controller
{
    /**
     * The current login user
     *
     * @var App\Core\Models\User
     */
    protected $user;

    /**
     * Path to a folder under `views`. Use by render() for quicker returning
     * a view
     *
     * @var string
     */
    protected $viewPath;

    protected $customViewPath = [];

    /**
     * Constructor
     */
    public function __construct()
	{
        // Set the current user
        $this->user = Confide::user();
    }

    /**
     * Relative path to the folder containing View templates
     *
     * @return string
     */
    protected function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Array keeps custom view paths
     *
     * @return string
     */
    protected function customViewPath()
    {
        return $this->customViewPath;
    }

    /**
     * Change custom view for renderList function
     * Sometimes we don't use index page for listing
     *
     * @return string
     */
    public function makeViewPath($view)
    {
        $viewPath =  $this->customViewPath();
        if (array_key_exists($view, $this->customViewPath())) {
            return $viewPath[$view];
        }
        return $this->getViewPath() . '.' . $view;
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     * For lazy people, use this method to quick refer to a view template
     *
     * @param string $path
     * @param array $data
     *
     * @return View
     */
    protected function render($path, $data = [])
    {
        $viewPath = $this->getViewPath();
        $path = (!empty($viewPath))
            ? $viewPath.'.'.$path
            : $path;
        return \View::make($path, $data);
    }

    /**
     * Create a new message bag containing success messages
     *
     * @param mixed  $content
     * @param string $title   (optional)
     *
     * @return MessageBag
     */
    protected function successMessageBag($content, $title = null)
    {
        if ($title === null) {
            $title = trans('common.success');
        }

        $content = (array) $content;

        return new MessageBag([
            'success' => [
                'title'   => $title,
                'content' => $content
            ]
        ]);
    }

    /**
     * Create a new message bag for errors
     *
     * @return MessageBag
     */
    protected function errorMessageBag()
    {
        return new MessageBag(func_get_args());
    }
}
