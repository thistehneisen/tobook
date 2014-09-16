<?php namespace App\Consumers\Controllers;

use Input;
use App\Core\Controllers\Base;
use App\Appointment\Traits\Crud;

class Consumer extends Base
{
    use Crud;
    protected $modelClass = 'App\Consumers\Models\Consumer';
    protected $langPrefix = 'co';

    protected function upsertHandler($item)
    {
        $item->saveOrFail();
        $item->fill(Input::all());
        $item->users()->detach($this->user->id);
        $item->users()->attach($this->user, ['is_visible' => true]);

        return $item;
    }
}
