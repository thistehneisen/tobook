<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Report\Statistics as StatisticsModel;
use Config;
use Input;
use Redirect;
use Carbon\Carbon;

class Statistics extends Base
{
	protected $viewPath = 'admin.statistics';

	public function index()
	{

		$today = Carbon::now();
        $start = Input::has('start')
            ? carbon_date(Input::get('start'))
            : $today->copy()->subMonth();
        $end = Input::has('end')
            ? carbon_date(Input::get('end'))
            : $today;
     
        return $this->render('index', [
            'start'  => str_date($start),
            'end'    => str_date($end),
            'report' =>  new StatisticsModel($start, $end),
        ]);
	}
}