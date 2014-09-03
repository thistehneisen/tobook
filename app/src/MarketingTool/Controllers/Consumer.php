<?php
namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator;
use \App\MarketingTool\Models\Consumer as ConsumerModel;
use Confide;

class Consumer extends \App\Core\Controllers\Base {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the consumers
        $consumers = ConsumerModel::where('user_id', '=', Confide::user()->id)->get();

        // load the view and pass the consumers
        return View::make('modules.mt.consumers.index')
            ->with('consumers', $consumers);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $consumer = ConsumerModel::find($id);

        return View::make('modules.mt.consumers.show')
            ->with('consumer', $consumer);
    }
}
