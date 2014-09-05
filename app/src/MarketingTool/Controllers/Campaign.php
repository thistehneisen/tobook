<?php
namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator, Response, File, Mandrill, Config;
use \App\MarketingTool\Models\Campaign as CampaignModel;
use \App\MarketingTool\Models\Template as TemplateModel;
use \App\MarketingTool\Models\Group as GroupModel;
use \App\MarketingTool\Models\GroupConsumer as GroupConsumerModel;
use \App\MarketingTool\Models\History as HistoryModel;
use \App\MarketingTool\Models\Sms as SmsModel;
use \App\MarketingTool\Models\Consumer as ConsumerModel;
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
        
        $orgCampaign = CampaignModel::find($campaign_id);
        
        $newCampaign = new CampaignModel();
        $newCampaign->subject = $subject;
        $newCampaign->content = $orgCampaign->content;
        $newCampaign->from_email = $orgCampaign->from_email;
        $newCampaign->from_name = $orgCampaign->from_name;
        $newCampaign->status = 'DRAFT';
        $newCampaign->campaign_code = str_random(32);
        $newCampaign->user_id = Confide::user()->id;
        $newCampaign->save();
        
        return Response::json(['result' => 'success']);
    }
    

    /**
     * Send Bulk Campaign To Consumers
     */
    public function sendIndividual()
    {
        $consumerIds = Input::get('consumer_ids');
        $campaignId = Input::get('campaign_id');

        $rules = [];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Response::json(['result' => 'failed']);
        } else {
            // create consumers group
            $group = new GroupModel;
            $group->name = "INDIVIDUAL";
            $group->is_individual = 1;
            $group->user_id = Confide::user()->id;
            $group->save();
            $groupId = $group->id;
            
            // change campaign status - SENT
            $campaign = CampaignModel::find($campaignId);
            $campaign->status = 'SENT';
            $campaign->save();
            
            $emails = [];
            foreach ($consumerIds as $key => $consumerId) {
                $groupConsumer = new GroupConsumerModel;
                $groupConsumer->group_id = $groupId;
                $groupConsumer->consumer_id = $consumerId;
                $groupConsumer->user_id = Confide::user()->id;
                $groupConsumer->save();
                
                $emails[] = array('email' => $groupConsumer->consumer->email);
            }
            
            // send email
            try {
                $mandrill = new Mandrill(Config::get('mail.password'));
                $message = array(
                                'html' => $campaign->content,
                                'subject' => $campaign->subject,
                                'from_email' => $campaign->from_email,
                                'from_name' => $campaign->from_name,
                                'to' => $emails
                );
                $async = false;
                $result = $mandrill->messages->send($message, $async);
            } catch(Mandrill_Error $e) {
                return Response::json(['result' => 'failed']);
            }
            
            // store history
            $history = new HistoryModel;
            $history->campaign_id = $campaignId;
            $history->group_id = $groupId;
            $history->user_id = Confide::user()->id;
            $history->save();

            return Response::json(['result' => 'success']);
        }
    }
    
    /**
     * Send Bulk Campaign To Groups
     */
    public function sendGroup()
    {
        $groupIds = Input::get('group_ids');
        $campaignId = Input::get('campaign_id');
        $rules = [];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Response::json(['result' => 'failed']);
        } else {
            // change campaign status - SENT
            $campaign = CampaignModel::find($campaignId);
            $campaign->status = 'SENT';
            $campaign->save();
            
            $emails = [];
            foreach ($groupIds as $key => $groupId) {
                $groupConsumers = GroupConsumerModel::where('group_id', '=', $groupId)->get();
                foreach ($groupConsumers as $key => $consumer) {
                    $emails[] = array('email' => $consumer->consumer->email);
                }

                // store history
                $history = new HistoryModel;
                $history->campaign_id = $campaignId;
                $history->group_id = $groupId;
                $history->user_id = Confide::user()->id;
                $history->save();
            }
            
            // send email
            try {
                $mandrill = new Mandrill(Config::get('mail.password'));
                $message = array(
                                'html' => $campaign->content,
                                'subject' => $campaign->subject,
                                'from_email' => $campaign->from_email,
                                'from_name' => $campaign->from_name,
                                'to' => $emails
                );
                $async = false;
                $result = $mandrill->messages->send($message, $async);
            } catch(Mandrill_Error $e) {
                return Response::json(['result' => 'failed']);
            }
            return Response::json(['result' => 'success']);
        }
    }    
}
