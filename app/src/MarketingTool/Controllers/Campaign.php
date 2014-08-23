<?php
namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator;
use \App\MarketingTool\Models\Campaign as CampaignModel;
use Confide;

class Campaign extends \App\Core\Controllers\Base {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the campaigns
        $campaigns = CampaignModel::all();
    
        // load the view and pass the campaigns
        return View::make('modules.mt.campaigns.index')
            ->with('campaigns', $campaigns);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('modules.mt.campaigns.create');
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'subject'    => 'required',
            'content'    => 'required',
            'from_email' => 'required|email',
            'from_name'  => 'required',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $campaign = new CampaignModel;
            $campaign->subject = Input::get('subject');
            $campaign->content = Input::get('content');
            $campaign->from_email = Input::get('from_email');
            $campaign->from_name = Input::get('from_name');
            $campaign->status = (Input::get('status') === '') ? 'DRAFT' : Input::get('status');
            // generate unique campaign_code for tracking statistics
            $campaign->campaign_code = str_random(32);
            $campaign->user_id = Confide::user()->id;;
            $campaign->save();
    
            Session::flash('message', 'Successfully created!');
            return Redirect::route('mt.campaigns.index');
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
        $campaign = CampaignModel::find($id);
    
        return View::make('modules.mt.campaigns.show')
            ->with('campaign', $campaign);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $campaign = CampaignModel::find($id);
    
        return View::make('modules.mt.campaigns.edit')
            ->with('campaign', $campaign);
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
            'subject'    => 'required',
            'content'    => 'required',
            'from_email' => 'required|email',
            'from_name'  => 'required',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $campaign = CampaignModel::find($id);
            $campaign->subject = Input::get('subject');
            $campaign->content = Input::get('content');
            $campaign->from_email = Input::get('from_email');
            $campaign->from_name = Input::get('from_name');
            $campaign->save();
    
            return Redirect::route('mt.campaigns.index')
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
        $campaign = CampaignModel::find($id);
        $campaign->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('mt.campaigns.index');
    }
    

}
