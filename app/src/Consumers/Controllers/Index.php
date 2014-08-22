<?php namespace App\Consumers\Controllers;

use Config, Redirect, Input;
use Consumer;
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
                    trans('co.updated')
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
}
