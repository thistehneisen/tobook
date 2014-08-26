<?php namespace App\Core\Controllers;

use Confide;
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
     * Relative path in `views` folder for quicker lookup
     *
     * @var string
     */
    protected $viewPath;

    /**
     * Do stuff that are available thoughout all controllers
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
     * For lazy people, use this method to quick refer to a view template
     *
     * @param string $path
     * @param array $data
     *
     * @return View
     */
    protected function render($path, $data = [])
    {
        $path = (!empty($this->getViewPath()))
            ? $this->getViewPath().'.'.$path
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
