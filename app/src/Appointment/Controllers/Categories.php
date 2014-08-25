<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Traits\Crud;

class Categories extends AsBase
{
    use Crud;

    /**
     * {@inheritdoc}
     */
    protected function getModelClass()
    {
        return 'App\Appointment\Models\ServiceCategory';
    }

    protected function getViewPath()
    {
        return 'modules.as.services.category';
    }

    /**
     * Show the form to insert or update a record
     *
     * @param int $id Optional. Item's ID
     *
     * @return View
     */
    public function upsert($id = null)
    {
        $data = [];
        if ($id !== null) {
            $data['item'] = ServiceCategory::findOrFail($id);
        }

        return View::make('modules.as.services.category.form', $data);
    }

    /**
     * Handle upsert
     *
     * @param int $id Optional. Item's ID
     *
     * @return Redirect
     */
    public function doUpsert($id = null)
    {
        $errors = $this->errorMessageBag(trans('common.err.unexpected'));

        try {
            $category = ($id !== null)
                ? ServiceCategory::findOrFail($id)
                : new ServiceCategory;

            $category->fill(Input::all());
            // Attach user
            $category->user()->associate($this->user);
            $category->saveOrFail();

            $message = ($id !== null)
                ? trans('as.services.success_add_category')
                : trans('as.services.success_edit_category');

            return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag($message));
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    /**
     * Delete a category
     *
     * @param int $id
     *
     * @return Redirect
     */
    public function delete($id)
    {
        $item = $this->categoryModel->findOrFail($id);
        $item->delete();

        return Redirect::route('as.services.categories')
            ->with(
                'messages',
                $this->successMessageBag('ID #'.$id.' was deleted')
            );
    }

    public function destroy()
    {
        $categories = Input::get('categories', []);
        try {
            $this->categoryModel->destroy($categories);
            $data['success'] = true;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['error'] = $ex->getMessage();
        }
        return Response::json($data);
    }
}
