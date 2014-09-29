<?php namespace App\LoyaltyCard\Controllers;

use View, Input, Confide;
use App\Core\Controllers\Base;
use App\LoyaltyCard\Controllers\ConsumerRepository as ConsumerRepository;
use App\Consumers\Models\Consumer as Core;

class Consumer extends Base
{
    use \CRUD;

    protected $consumerRepository;
    protected $viewPath = 'modules.lc.consumers';
    protected $crudOptions = [
        'modelClass'    => 'App\LoyaltyCard\Models\Consumer',
        'langPrefix'    => 'loyalty-card',
        'layout'        => 'layouts.default',
        'showTab'       => true,
    ];

    public function __construct(ConsumerRepository $consumerRp)
    {
        parent::__construct();
        $this->consumerRepository = $consumerRp;
    }

    protected function upsertHandler($item)
    {
        $core = $item->consumer;

        if ($core === null) {
            $core = Core::make(Input::all());
            $core->users()->detach($this->user->id);
            $core->users()->attach($this->user, ['is_visible' => true]);
            $item->total_points = 0;
            $item->total_stamps = '';
            $item->user()->associate(Confide::user());
            $item->consumer()->associate($core);
            $item->saveOrFail();
        } else {
            $core->fill(Input::all());
            $core->save();
        }

        return $item;
    }
}
