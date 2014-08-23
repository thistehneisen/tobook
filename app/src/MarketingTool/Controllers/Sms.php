<?php
namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator;
use \App\MarketingTool\Models\Sms as SmsModel;
use Confide;

class Sms extends \App\Core\Controllers\Base {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the smss
        $smss = SmsModel::where('user_id', '=', Confide::user()->id)->get();
    
        // load the view and pass the smss
        return View::make('modules.mt.smss.index')
            ->with('smss', $smss);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('modules.mt.smss.create');
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'title'      => 'required',
            'content'    => 'required',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $sms = new SmsModel;
            $sms->title = Input::get('title');
            $sms->content = Input::get('content');
            $sms->user_id = Confide::user()->id;
            $sms->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('mt.smss.index');
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
        $sms = SmsModel::find($id);
    
        return View::make('modules.mt.smss.show')
            ->with('sms', $sms);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $sms = SmsModel::find($id);
    
        return View::make('modules.mt.smss.edit')
            ->with('sms', $sms);
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
            'title'      => 'required',
            'content'    => 'required',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $sms = SmsModel::find($id);
            $sms->title = Input::get('title');
            $sms->content = Input::get('content');
            $sms->save();
    
            return Redirect::route('mt.smss.index')
                ->with('message', 'Successfully created!');
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
        $sms = SmsModel::find($id);
        $sms->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('mt.smss.index');
    }
    

}
