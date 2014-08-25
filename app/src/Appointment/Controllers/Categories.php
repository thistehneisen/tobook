<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\ServiceCategory;

class Categories extends AsBase
{

     /**
     * Show all categories of the current user and a form to add new category
     *
     * @return View
     */
    public function categories()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $categories = $this->categoryModel
            ->ofCurrentUser()
            ->paginate($perPage);

        return View::make('modules.as.services.categories', [
            'categories' => $categories
        ]);
    }

    /**
    * Show empty category form
    *
    * @return View
    */
    public function create(){
        return View::make('modules.as.services.category.form');
    }

    /**
     * Handle user input to create a new category
     *
     * @return Redirect
     */
    public function doCreate()
    {
        $errors = $this->errorMessageBag(trans('common.err.unexpected'));

        try {
            $category = new ServiceCategory;
            $category->fill(Input::all());
            // Attach user
            $category->user()->associate($this->user);
            $category->saveOrFail();

            return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag(
                    trans('as.services.success_add_category')
                ));
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    /**
     * Show the form to edit a category
     *
     * @param int $id
     *
     * @return View
     */
    public function edit($id)
    {
        $category = ServiceCategory::findOrFail($id);
        return View::make('modules.as.services.category.form', [
            'category' => $category
        ]);
    }

    /**
     * Do edit
     *
     * @param int $id
     *
     * @return Redirect
     */
    public function doEdit($id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->fill(Input::all());
        // Attach user
        $category->user()->associate($this->user);
        $category->saveOrFail();

        return Redirect::route('as.services.categories')
            ->with('messages', $this->successMessageBag('Category with ID #' . $id . ' was updated.'));
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
        try{
            $this->categoryModel->destroy($categories);
            $data['success'] = true;
        } catch (\Exception $ex){
            $data['success'] = false;
            $data['error'] = $ex->getMessage();
        }
        return Response::json($data);
    }
}
