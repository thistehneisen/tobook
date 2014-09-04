<?php
namespace App\MarketingTool\Controllers;

use Illuminate\Mail\Transport\MandrillTransport;

use Input, Session, Redirect, View, Validator, Response;
use \App\MarketingTool\Models\History as HistoryModel;
use \App\MarketingTool\Models\Sms as SmsModel;
use \App\MarketingTool\Models\Consumer as ConsumerModel;
use \App\MarketingTool\Models\Group as GroupModel;
use \App\MarketingTool\Models\GroupConsumer as GroupConsumerModel;
use \App\MarketingTool\InfoBip as InfoBip;
use Confide;

class Sms extends \App\Core\Controllers\Base {
    
    protected $infobip;
    
    public function __construct() {
        $this->infobip = new InfoBip;
        $this->infobip->InfoBip('varaa6', 'varaa12');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    
    public function index()
    {
        // get all the sms
        $sms = SmsModel::where('user_id', '=', Confide::user()->id)->get();
    
        // load the view and pass the sms
        return View::make('modules.mt.sms.index')
            ->with('sms', $sms);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('modules.mt.sms.create');
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
            return Redirect::route('mt.sms.index');
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
    
        return View::make('modules.mt.sms.show')
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
    
        return View::make('modules.mt.sms.edit')
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
    
            return Redirect::route('mt.sms.index')
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
        return Redirect::route('mt.sms.index');
    }
    
    /**
     * Send Bulk Sms To Consumers
     */
    public function send_individual()
    {
        $consumer_ids = Input::get('consumer_ids');
        $sms_id = Input::get('sms_id');
        
        $rules = [];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Response::json(['result' => 'failed', ]);
        } else {
            // create consumers group
            $group = new GroupModel;
            $group->name = "INDIVIDUAL";
            $group->is_individual = 1;
            $group->user_id = Confide::user()->id;
            $group->save();
            $group_id = $group->id;
            
            $sms = SmsModel::find($sms_id);
            $sms->status = 'SENT';
            $sms->save();

            foreach ($consumer_ids as $key => $value) {
                $group_consumer = new GroupConsumerModel;
                $group_consumer->group_id = $group_id;
                $group_consumer->consumer_id = $value;
                $group_consumer->user_id = Confide::user()->id;
                $group_consumer->save();
                
                $consumer = ConsumerModel::find($value);
                $phone = $consumer->phone;
                
                $this->infobip->send_sms_infobip($sms['title'], $phone, $sms['content']);
            }
            
            $history = new HistoryModel;
            $history->sms_id = $sms_id;
            $history->group_id = $group_id;
            $history->user_id = Confide::user()->id;
            $history->save();
            
            return Response::json(['result' => 'success', ]);
        }        
    }
    
    /**
     * Send Bulk Sms To Groups
     */
    public function send_group()
    {
        $group_ids = Input::get('group_ids');
        $sms_id = Input::get('sms_id');
        $rules = [];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Response::json(['result' => 'failed', ]);
        } else {
            // create consumers group
            $sms = SmsModel::find($sms_id);
            $sms->status = 'SENT';
            $sms->save();
        
            foreach ($group_ids as $key => $group_id) {
                $group_consumers = GroupConsumerModel::where('group_id', '=', $group_id)->get();
                foreach ($group_consumers as $key => $consumer) {
                    $this->infobip->send_sms_infobip($sms['title'], $consumer->consumer->phone, $sms['content']);
                }
                $history = new HistoryModel;
                $history->sms_id = $sms_id;
                $history->group_id = $group_id;
                $history->user_id = Confide::user()->id;
                $history->save();                
            }
            
            return Response::json(['result' => 'success', ]);
        }        
    }    
}
