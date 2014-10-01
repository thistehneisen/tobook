<?php namespace App\LoyaltyCard\Controllers;

use Input;
use App\Core\Controllers\Base;

class Voucher extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.lc.vouchers';
    protected $crudOptions = [
        'modelClass'    => 'App\LoyaltyCard\Models\Voucher',
        'langPrefix'    => 'loyalty-card',
        'layout'        => 'modules.lc.layout',
        'indexFields'   => ['name', 'required', 'value', 'type', 'is_active'],
        'lomake' => [
            'type' => [
                'type' => 'App\Core\Fields\TypeDropdown'
            ],
        ],
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->total_used = 0;
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
