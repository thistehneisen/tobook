<?php
namespace App\FlashDeal\Controllers;

use Input, Session, Redirect, View, Validator;
use \App\FlashDeal\Models\Service as ServiceModel;
use \App\FlashDeal\Models\Flash as FlashModel;
use \App\FlashDeal\Models\FlashSold as FlashSoldModel;
use Confide;

class Flash extends \App\Core\Controllers\Base {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the flashs
        $flashs = FlashModel::where('user_id', '=', Confide::user()->id)->get();
        
        // load the view and pass the services
        return View::make('modules.fd.flashs.index')
            ->with('flashs', $flashs);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // get all the service
        $services = ServiceModel::where('user_id', '=', Confide::user()->id)->get();
        
        return View::make('modules.fd.flashs.create')
            ->with('services', $services);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'service_id'          => 'required',
            'discounted_price'    => 'required|numeric',
            'count'               => 'required|numeric',
            'flash_date'          => 'required|date_format:"Y-m-d"',
            'start_time'          => 'required',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $service = ServiceModel::find(Input::get('service_id'));
            
            $flash = new FlashModel;
            $flash->service_id = Input::get('service_id');
            $flash->discounted_price = Input::get('discounted_price');
            $flash->count = Input::get('count');
            $flash->flash_date = Input::get('flash_date');
            $flash->start_time = Input::get('start_time');
            $flash->end_time = date("H:i:s", strtotime($service->length." minutes", strtotime(Input::get('start_time'))));
            $flash->user_id = Confide::user()->id;
            $flash->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('fd.flashs.index');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $flash = FlashModel::find($id);
        
        // get all the service
        $services = ServiceModel::where('user_id', '=', Confide::user()->id)->get();        
    
        return View::make('modules.fd.flashs.show')
            ->with('flash', $flash)
            ->with('services', $services);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $flash = FlashModel::find($id);
        
        // get all the services
        $services = ServiceModel::where('user_id', '=', Confide::user()->id)->get();
    
        return View::make('modules.fd.flashs.edit')
            ->with('flash', $flash)
            ->with('services', $services);
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $rules = [
            'service_id'          => 'required',        
            'discounted_price'    => 'required|numeric',
            'count'               => 'required|numeric',
            'flash_date'          => 'required|date_format:"Y-m-d"',
            'start_time'          => 'required',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $service = ServiceModel::find(Input::get('service_id'));
            
            $flash = FlashModel::find($id);
            $flash->service_id = Input::get('service_id');
            $flash->discounted_price = Input::get('discounted_price');
            $flash->count = Input::get('count');
            $flash->flash_date = Input::get('flash_date');
            $flash->start_time = Input::get('start_time');
            $flash->end_time = date("H:i:s", strtotime($service->length." minutes", strtotime(Input::get('start_time'))));
            $flash->user_id = Confide::user()->id;
            $flash->save();
            
            return Redirect::route('fd.flashs.index')
                ->with('message', 'Successfully updated!');
        }
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $flash = FlashModel::find($id);
        $flash->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('fd.flashs.index');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function sold()
    {
        $flashs = FlashModel::where('user_id', '=', Confide::user()->id)->get();
        $solds = [];
        foreach ($flashs as $flash) {
            foreach ($flash->solds as $sold) {
                $solds[] = $sold;
            }
        }
        // load the view and pass the coupons
        return View::make('modules.fd.flashs.sold')
            ->with('flashs', $solds);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function active()
    {
        $actives = FlashModel::where('user_id', '=', Confide::user()->id)
                    ->whereRaw('concat(flash_date, " ", end_time) >= now()')
                    ->whereRaw('concat(flash_date, " ", start_time) <= now()')
                    ->get();

        // load the view and pass the flashs
        return View::make('modules.fd.flashs.active')
            ->with('flashs', $actives);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function expire()
    {
        $expires = FlashModel::where('user_id', '=', Confide::user()->id)
                    ->whereRaw('concat(flash_date, " ", end_time) < now()')
                    ->get();
    
        // load the view and pass the flashs
        return View::make('modules.fd.flashs.expire')
            ->with('flashs', $expires);
    }    

}
