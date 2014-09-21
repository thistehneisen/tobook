<?php namespace App\Consumers\Controllers;

use Input, DB, Confide, Request, Response;
use App\Core\Controllers\Base;

class Consumer extends Base
{
    use \CRUD;
    protected $viewPath = 'modules.co';
    protected $crudOptions = [
        'modelClass'    => 'App\Consumers\Models\Consumer',
        'langPrefix'    => 'co',
        'indexFields'   => ['first_name', 'last_name', 'email', 'phone'],
        'layout'        => 'layouts.default',
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->saveOrFail();
        $item->users()->detach($this->user->id);
        $item->users()->attach($this->user, ['is_visible' => true]);

        return $item;
    }

    public function getHistory()
    {
        $consumerId = Input::get('id');
        $service = Input::get('service');
        $history = null;

        switch ($service) {
            case 'as':
                $history = DB::table('as_bookings')
                            ->join('as_booking_services', 'as_bookings.id', '=', 'as_booking_services.booking_id')
                            ->join('as_services', 'as_booking_services.service_id', '=', 'as_services.id')
                            ->where('as_bookings.user_id', Confide::user()->id)
                            ->where('as_bookings.consumer_id', $consumerId)
                            ->select('as_bookings.id', 'as_bookings.uuid', 'as_booking_services.date', 'as_bookings.start_at', 'as_bookings.end_at', 'as_services.name', 'as_bookings.notes', 'as_bookings.created_at')
                            ->take(10)
                            ->get();



                break;

            case 'lc':

                break;
        }

        if (Request::ajax()) {
            return Response::json([
                'success' => true,
                'history' => $history,
            ]);
        }
    }
}
