<?php namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator, Response;
use App\MarketingTool\Models\Group as GroupModel;
use App\MarketingTool\Models\GroupConsumer as GroupConsumerModel;
use App\MarketingTool\Models\Campaign as CampaignModel;
use App\MarketingTool\Models\Sms as SmsModel;
use Confide;

class Group extends \App\Core\Controllers\Base {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the groups
        $groups = GroupModel::where('user_id', '=', Confide::user()->id)
                        ->where('is_individual', '=', false)
                        ->paginate(20);
        
        $campaigns = CampaignModel::where('user_id', '=', Confide::user()->id)
                        ->where('status', '=', 'DRAFT')
                        ->get();
        
        $sms = SmsModel::where('user_id', '=', Confide::user()->id)
                        ->where('status', '=', 'DRAFT')
                        ->get();

        // load the view and pass the groups
        return View::make('modules.mt.groups.index')
            ->with('groups', $groups)
            ->with('campaigns', $campaigns)
            ->with('sms', $sms);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $consumer_ids = Input::get('consumer_ids');
        $group_name = Input::get('group_name');
        
        $rules = [
            'group_name' => 'required',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Response::json(['result' => 'failed']);
        } else {
            $group = new GroupModel;
            $group->name = $group_name;
            $group->is_individual = 0;
            $group->user_id = Confide::user()->id;
            $group->save();
            $group_id = $group->id;
            
            foreach ($consumer_ids as $key => $consumer) {
                $group_consumer = new GroupConsumerModel;
                $group_consumer->group_id = $group_id;
                $group_consumer->consumer_id = $consumer;
                $group_consumer->user_id = Confide::user()->id;
                $group_consumer->save();
            }
            return Response::json(['result' => 'success']);
        }
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'name'       => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $group = new GroupModel;
            $group->name = Input::get('name');
            $group->is_individual = 0;
            $group->user_id = Confide::user()->id;
            $group->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('mt.groups.index');
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
        $group = GroupModel::find($id);
    
        return View::make('modules.mt.groups.show')
            ->with('group', $group);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $group = GroupModel::find($id);
    
        return View::make('modules.mt.groups.edit')
            ->with('group', $group);
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
            'name'       => 'required',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $group = GroupModel::find($id);
            $group->name = Input::get('name');
            $group->user_id = Confide::user()->id;
            $group->save();

            return Redirect::route('mt.groups.index')
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
        $groupConsumer = GroupConsumerModel::where('group_id', '=',$id);
        $groupConsumer->delete();        
        
        $group = GroupModel::find($id);
        $group->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('mt.groups.index');
    }
    

}
