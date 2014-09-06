<?php
namespace App\FlashDeal\Controllers;

use Input, Session, Redirect, View, Validator;
use \App\FlashDeal\Models\Service as ServiceModel;
use \App\FlashDeal\Models\ServiceCategory as ServiceCategoryModel;
use Confide;

class Service extends \App\Core\Controllers\Base {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the services
        $services = ServiceModel::where('user_id', '=', Confide::user()->id)
                        ->get();
        
        // load the view and pass the services
        return View::make('modules.fd.services.index')
            ->with('services', $services);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // get all the service category
        $categories = ServiceCategoryModel::all();
        
        return View::make('modules.fd.services.create')
            ->with('categories', $categories);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'name'    => 'required',
            'length'  => 'required|numeric',
            'price'   => 'required|numeric',
            'category_id' => 'required',
            'sms_confirmation'   => 'required',
            'account_owner'      => 'required',
            'bank_account_number'=> 'required',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $service = new ServiceModel;
            $service->name = Input::get('name');
            $service->length = Input::get('length');
            $service->price = Input::get('price');
            $service->category_id = Input::get('category_id');
            $service->sms_confirmation = Input::get('sms_confirmation');
            $service->account_owner = Input::get('account_owner');
            $service->bank_account_number = Input::get('bank_account_number');
            $service->user_id = Confide::user()->id;
            $service->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('fd.services.index');
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
        $service = ServiceModel::find($id);
    
        return View::make('modules.fd.services.show')
            ->with('service', $service);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $service = ServiceModel::find($id);
        
        // get all the service category
        $categories = ServiceCategoryModel::all();
    
        return View::make('modules.fd.services.edit')
            ->with('service', $service)
            ->with('categories', $categories);
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
            'name'    => 'required',
            'length'  => 'required|numeric',
            'price'   => 'required|numeric',
            'category_id' => 'required',
            'sms_confirmation'   => 'required',
            'account_owner'      => 'required',
            'bank_account_number'=> 'required',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $service = ServiceModel::find($id);
            $service->name = Input::get('name');
            $service->length = Input::get('length');
            $service->price = Input::get('price');
            $service->category_id = Input::get('category_id');
            $service->sms_confirmation = Input::get('sms_confirmation');
            $service->account_owner = Input::get('account_owner');
            $service->bank_account_number = Input::get('bank_account_number');
            $service->user_id = Confide::user()->id;
            $service->save();
            
            return Redirect::route('fd.services.index')
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
        $service = ServiceModel::find($id);
        $service->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('fd.services.index');
    }
    

}
