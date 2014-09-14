<?php namespace App\FlashDeal\Controllers;

use Input, View;
use App\Core\Controllers\Base;
use App\Appointment\Traits\Crud;
use App\Core\Models\BusinessCategory;

class Services extends Base
{
    use Crud;

    protected $viewPath = 'modules.fd.services';
    protected $langPrefix = 'fd.services';
    protected $modelClass = 'App\FlashDeal\Models\Service';
    protected $crudLayout = 'modules.fd.layout';

    public function __construct()
    {
        parent::__construct();

        // Get all business categories
        $categories = BusinessCategory::getAll();
        View::share('businessCategories', $categories);
    }

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $category = BusinessCategory::findOrFail(Input::get('business_category_id'));

        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->businessCategory()->associate($category);
        $item->saveOrFail();

        return $item;
    }
}
