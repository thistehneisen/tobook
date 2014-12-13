<?php namespace App\Consumers\Controllers;

use App\Consumers\Models\History;
use App\Core\Controllers\Base;
use App\Lomake\Fields\HtmlField;
use Confide;
use Config;
use DB;
use Input;
use Lang;
use Session;
use View;

class EmailTemplate extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.co.email_templates';

    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\EmailTemplate',
        'langPrefix' => 'co.email_templates',
        'indexFields' => ['subject', 'from_email', 'from_name'],
        'lomake' => [
            'content' => [
                'type' => 'html_field',
            ],
        ],
        'layout' => 'modules.co.layout',
        'actionsView' => 'modules.co.email_templates.actions',
        'showTab' => false,
    ];

    protected function upsertHandler($item)
    {
        $item->fill([
            'subject' => Input::get('subject'),
            'from_email' => Input::get('from_email'),
            'from_name' => Input::get('from_name'),
            'content' => HtmlField::filterInput(Input::all(), 'content'),
        ]);
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }

    public function history()
    {
        $historyQuery = null;

        $campaignId = intval(Input::get('campaign_id', 0));
        if (!empty($campaignId)) {
            $campaign = \App\Consumers\Models\EmailTemplate::ofCurrentUser()->findOrFail($campaignId);
            $historyQuery = $campaign->histories();
        } else {
            $historyQuery = History::ofCurrentUser();
        }

        $historyQuery->orderBy('created_at', 'desc');
        $historyQuery->with(['group', 'consumer', 'campaign']);
        $historyQuery->whereNotNull('campaign_id');

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $histories = $historyQuery->paginate($perPage);

        return View::make('modules.co.history.email', [
            'histories' => $histories,
        ]);
    }
}
