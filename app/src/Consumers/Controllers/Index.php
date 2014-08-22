<?php namespace App\Consumers\Controllers;

use Config, Redirect, Input, Validator, Confide, Consumer;
use App\Core\Controllers\Base;

class Index extends Base
{
    protected $viewPath = 'modules.co.';

    /**
     * Show all consumers of the current user
     *
     * @return View
     */
    public function index()
    {
        $consumers = Consumer::ofCurrentUser()
            ->visible()
            ->paginate(Config::get('view.perPage'));

        return $this->render('index.index', [
            'consumers' => $consumers
        ]);
    }

    /**
     * Edit a consumer
     *
     * @param int $id Consumer's ID
     *
     * @return View
     */
    public function edit($id)
    {
        $consumer = Consumer::find($id);
        $fields = [
            'first_name' => ['label' => trans('co.first_name')],
            'last_name'  => ['label' => trans('co.last_name')],
            'email'      => ['label' => trans('co.email')],
            'phone'      => ['label' => trans('co.phone')],
            'address'    => ['label' => trans('co.address')],
            'city'       => ['label' => trans('co.city')],
            'postcode'   => ['label' => trans('co.postcode')],
            'country'    => ['label' => trans('co.country')],
        ];

        return $this->render('index.edit', [
            'consumer' => $consumer,
            'fields'   => $fields
        ]);
    }

    /**
     * Save data into database
     *
     * @param int $id Consumer's ID
     *
     * @return Redirect
     */
    public function doEdit($id)
    {
        $errors = $this->errorMessageBag(trans('co.exception'));

        try {
            $consumer = Consumer::findOrFail($id);
            $consumer->fill(Input::all());
            $consumer->saveOrFail();

            return Redirect::route('co.index')
                ->with('messages', $this->successMessageBag(
                    trans('co.edit_success')
                ));
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        } catch (\Illuminate\Database\QueryException $ex) {
            $errors = $this->errorMessageBag(trans('co.query_exception'));
        }

        return Redirect::back()
            ->withInput()
            ->withErrors($errors);
    }

    /**
     * Perform bulk action on selected consumers
     *
     * @return Redirect
     */
    public function bulk()
    {
        $v = Validator::make(Input::all(), [
            'id' => 'required|array'
        ]);
        if ($v->fails()) {
            return Redirect::back()->withErrors($v, 'top');
        }

        $ids    = Input::get('id');
        $action = Input::get('action');
        if (!method_exists('Consumer', $action)) {
            return Redirect::back()
                ->withErrors($this->errorMessageBag(
                    trans('co.invalid_action')
                ));
        }

        // OK here we go
        $consumers = Consumer::with('users')->whereIn('id', $ids)->get();

        // Params that need for every actions
        $params = [
            'hide' => [Confide::user()->id]
        ];
        foreach ($consumers as $consumer) {
            if (isset($params[$action])) {
                call_user_func_array([$consumer, $action], $params[$action]);
            }
        }

        return Redirect::back()
            ->with('messages', $this->successMessageBag(
                trans('co.'.$action.'_success')
            ));
    }
}
