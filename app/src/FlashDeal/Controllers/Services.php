<?php namespace App\FlashDeal\Controllers;

use Input;
use App\Core\Controllers\Base;
use App\Appointment\Traits\Crud;

class Services extends Base
{
    use Crud;

    protected $viewPath = 'modules.fd.services';
    protected $langPrefix = 'fd.services';
    protected $modelClass = 'App\FlashDeal\Models\Service';
    protected $crudLayout = 'modules.fd.layout';

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
