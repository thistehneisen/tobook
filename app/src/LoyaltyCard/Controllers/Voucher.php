<?php namespace App\LoyaltyCard\Controllers;

use Input;
use App\Core\Controllers\Base;

class Voucher extends Base
{
    use \CRUD;

    protected $crudOptions = [
        'modelClass'    => 'App\LoyaltyCard\Models\Voucher',
        'langPrefix'    => 'lc.voucher',
        'layout'        => 'modules.lc.layout',
        'indexFields'   => ['name', 'required', 'value', 'type', 'is_active'],
        'lomake' => [
            'type' => [
                'type' => 'Dropdown',
            ],
        ],
    ];

    public function __construct() {
        parent::__construct();
        $this->crudOptions['lomake']['type']['values'] =[
            'Percent' => trans('lc.percent'),
            'Cash' => trans('lc.cash')
        ];
    }

    protected function upsertHandler($item)
    {

        $item->fill(Input::all());
        $item->total_used = 0;
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
