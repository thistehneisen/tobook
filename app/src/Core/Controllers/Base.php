<?php namespace App\Core\Controllers;

use Confide, View;
use Illuminate\Support\MessageBag, Settings;
use App\Core\Models\BusinessCategory;

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

    /**
     * Constructor
     */
    public function __construct()
	{
        // Set the current user
        $this->user = Confide::user();

        $categories = BusinessCategory::root()->get();
        // share this variables for all views (to construct the nav)
        View::share('_businessCategories', $categories);
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
