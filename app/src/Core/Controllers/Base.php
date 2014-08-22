<?php namespace App\Core\Controllers;

use Illuminate\Support\MessageBag, Settings;

class Base extends \Controller
{
    /**
     * Path to a folder under `views`. Use by render() for quicker returning
     * a view
     *
     * @var string
     */
    protected $viewPath;


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
     * Quicker return a view with specific path
     *
     * @param string $path
     * @param array $data
     *
     * @return View
     */
    protected function render($path, $data = [])
    {
        if ($this->viewPath !== null) {
            $path = $this->viewPath.$path;
        }

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
