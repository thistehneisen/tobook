<?php
namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator;
use \App\MarketingTool\Models\Setting as SettingModel;
use \App\MarketingTool\Models\Sms as SmsModel;
use \App\MarketingTool\Models\Campaign as CampaignModel;
use Confide;

class Setting extends \App\Core\Controllers\Base {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the settings
        $settings = SettingModel::where('user_id', '=', Confide::user()->id)->get();

        // load the view and pass the settings
        return View::make('modules.mt.settings.index')
            ->with('settings', $settings);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $sms = SmsModel::where('user_id', '=', Confide::user()->id)->get();
        $campaigns = CampaignModel::where('user_id', '=', Confide::user()->id)->get();
        
        return View::make('modules.mt.settings.create')
                ->with('sms', $sms)
                ->with('campaigns', $campaigns);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'module_type' => 'required',
            'counts_prev_booking' => 'numeric',
            'days_prev_booking' => 'numeric',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $setting = new SettingModel;
            $setting->module_type = Input::get('module_type');
            if (Input::get('campaign_id') !== '') {
                $setting->campaign_id = Input::get('campaign_id');
            }
            if (Input::get('sms_id') !== '') {
                $setting->sms_id = Input::get('sms_id');
            }
            
            if (Input::get('counts_prev_booking') !== '') {
                $setting->counts_prev_booking = Input::get('counts_prev_booking');
            }
            if (Input::get('days_prev_booking') !== '') {
                $setting->days_prev_booking = Input::get('days_prev_booking');
            }
            $setting->user_id = Confide::user()->id;
            $setting->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('mt.settings.index');
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
        $sms = SmsModel::where('user_id', '=', Confide::user()->id)->get();
        $campaigns = CampaignModel::where('user_id', '=', Confide::user()->id)->get();        
        $setting = SettingModel::find($id);
    
        return View::make('modules.mt.settings.show')
            ->with('setting', $setting)
            ->with('sms', $sms)
            ->with('campaigns', $campaigns);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $sms = SmsModel::where('user_id', '=', Confide::user()->id)->get();
        $campaigns = CampaignModel::where('user_id', '=', Confide::user()->id)->get();        
        $setting = SettingModel::find($id);
    
        return View::make('modules.mt.settings.edit')
            ->with('setting', $setting)
            ->with('sms', $sms)
            ->with('campaigns', $campaigns);
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
            'module_type' => 'required',
            'counts_prev_booking' => 'numeric',
            'days_prev_booking' => 'numeric',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $setting = SettingModel::find($id);
            $setting->module_type = Input::get('module_type');
            if (Input::get('campaign_id') !== '') {
                $setting->campaign_id = Input::get('campaign_id');
            } else {
                $setting->campaign_id = null;
            }
            
            if (Input::get('sms_id') !== '') {
                $setting->sms_id = Input::get('sms_id');
            } else {
                $setting->sms_id = null;
            }
            
            if (Input::get('counts_prev_booking') !== '') {
                $setting->counts_prev_booking = Input::get('counts_prev_booking');
            } else {
                $setting->counts_prev_booking = null;
            }
            if (Input::get('days_prev_booking') !== '') {
                $setting->days_prev_booking = Input::get('days_prev_booking');
            } else {
                $setting->days_prev_booking = null;
            }
            $setting->save();

            return Redirect::route('mt.settings.index')
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
        $setting = SettingModel::find($id);
        $setting->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('mt.settings.index');
    }
    

}
