<?php namespace App\Appointment\Traits;

use App, Input, Config;

trait Crud
{
    protected $model;

    /**
     * Return a model to interact with database
     *
     * @return Eloquent
     */
    protected function getModel()
    {
        if ($this->model === null) {
            $this->model = App::make($this->getModelClass());
        }

        return $this->model;
    }

    /**
     * Guess the model name based on controller name
     * If you're building a controller that doesn't have a model with the same
     * name, for example, ReportController using ConsumerModel, redefine this
     * method.
     *
     * @return string
     */
    protected function getModelClass()
    {
        // Because we have namespace like App\Appointments\Controllers
        $namespace = substr(__NAMESPACE__, 0, strrpos(__NAMESPACE__, '\\')).'\Models\\';
        return $namespace.str_singular(class_basename(__CLASS__));
    }

    /**
     * Show all items of the current user and a form to add new one
     *
     * @return View
     */
    public function index()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $this->getModel()
            ->ofCurrentUser()
            ->paginate($perPage);

        return $this->render('index', [
            'items' => $items
        ]);
    }
}
