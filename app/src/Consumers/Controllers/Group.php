<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use App\Consumers\Models\Campaign;
use Confide;
use DB;
use Input;
use Lang;
use Session;
use View;

class Group extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.co.groups';

    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\Group',
        'langPrefix' => 'co.groups',
        'indexFields' => ['name', 'consumers'],
        'layout' => 'modules.co.layout',
        'showTab' => false,
        'bulkActions' => [
            'destroy',
            'send_campaign',
        ],
        'presenters' => [
            'consumers' => 'App\Consumers\Presenters\GroupConsumers',
        ],
    ];

    public function bulkSendCampaign($ids)
    {
        $campaign = null;
        $campaignId = intval(Input::get('campaign_id'));
        if (!empty($campaignId)) {
            $campaign = Campaign::ofCurrentUser()->findOrFail($campaignId);
        }

        if (!empty($campaign)) {
            Campaign::sendGroups($campaign, $ids);

            return true;
        }

        $campaigns = Campaign::ofCurrentUser()->get();
        $campaignPairs = [];
        foreach ($campaigns as $campaign) {
            $campaignPairs[$campaign->id] = $campaign->subject;
        }

        $groups = \App\Consumers\Models\Group::ofCurrentUser()->whereIn('id', $ids)->get();

        return View::make('modules.co.groups.bulk_send_campaign', [
            'campaignPairs' => $campaignPairs,
            'groups' => $groups,
        ]);
    }
}
