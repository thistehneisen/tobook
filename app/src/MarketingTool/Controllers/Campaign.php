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
            return Redirect::route('modules.mt.campaigns.create')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            // generate unique campaign_code for tracking statistics
            $str_pattern = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $campaign_code = "";
            for( $i = 0 ; $i < 36; $i++ ){
                $rand = rand( 0, strlen($str_pattern) - 1 );
                $campaign_code = $campaign_code.$str_pattern[$rand];
            }

            $campaign = new CampaignModel;
            $campaign->subject = Input::get('subject');
            $campaign->content = Input::get('content');
            $campaign->from_email = Input::get('from_email');
            $campaign->from_name = Input::get('from_name');
            $campaign->status = Input::get('status')==''?'DRAFT':Input::get('status');
            $campaign->campaign_code = $campaign_code;
            $campaign->user_id = 1;
            $campaign->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('modules.mt.campaigns.index');
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
            return Redirect::route('modules.mt.campaigns.edit')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            $campaign = CampaignModel::find($id);
            $campaign->subject = Input::get('subject');
            $campaign->content = Input::get('content');
            $campaign->from_email = Input::get('from_email');
            $campaign->from_name = Input::get('from_name');
            $campaign->save();

            Session::flash('message', 'Successfully updated!');
            return Redirect::route('modules.mt.campaigns');
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
        return Redirect::route('modules.mt.campaigns');
	}


}
