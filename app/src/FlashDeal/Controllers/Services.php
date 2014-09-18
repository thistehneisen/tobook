<?php namespace App\FlashDeal\Controllers;

use Input, View;
use App\Core\Controllers\Base;
use App\Core\Models\BusinessCategory;

class Services extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.fd.services';
    protected $crudOptions = [
        'langPrefix' => 'fd.services',
        'modelClass' => 'App\FlashDeal\Models\Service',
        'layout'     => 'modules.fd.layout',
        'presenters' => [
            'price' => 'App\Olut\Presenters\Currency'
        ],
        'lomake' => [
            'business_category_id' => [
                'type' => 'App\Core\Fields\BusinessCategoryDropdown'
            ],
            'description' => [
                'type' => 'Textarea'
            ]
        ]
    ];

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
