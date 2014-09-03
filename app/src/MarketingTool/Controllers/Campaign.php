<?php
namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator, Response, File;
use \App\MarketingTool\Models\Campaign as CampaignModel;
use \App\MarketingTool\Models\Template as TemplateModel;
use Confide;

class Campaign extends \App\Core\Controllers\Base {
    
    public function __construct() {
        $user_id = Confide::user()->id;
        File::makeDirectory(public_path()."/assets/img/campaigns/".$user_id, 0775, true, true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the campaigns
        $campaigns = CampaignModel::where('user_id', '=', Confide::user()->id)->get();

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
        $templates = TemplateModel::where('user_id', '=', Confide::user()->id)->get();
        return View::make('modules.mt.campaigns.create')
            ->with('templates', $templates);
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
            $campaign->user_id = Confide::user()->id;
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
        $templates = TemplateModel::where('user_id', '=', Confide::user()->id)->get();

        return View::make('modules.mt.campaigns.edit')
            ->with('campaign', $campaign)
            ->with('templates', $templates);
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
    
    /**
     * Duplicate the campaign
     */
    public function duplication()
    {
        $campaign_id = Input::get('campaign_id');
        $subject = Input::get('subject');
        
        $org_campaign = CampaignModel::find($campaign_id);
        
        $new_campaign = new CampaignModel();
        $new_campaign->subject = $subject;
        $new_campaign->content = $org_campaign->content;
        $new_campaign->from_email = $org_campaign->from_email;
        $new_campaign->from_name = $org_campaign->from_name;
        $new_campaign->status = 'DRAFT';
        $new_campaign->campaign_code = str_random(32);
        $new_campaign->user_id = Confide::user()->id;
        $new_campaign->save();
        
        return Response::json(['result' => 'success', ]);
    }
}
