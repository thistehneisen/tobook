<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Setting;
use App\Lomake\FieldFactory;
use Config;
use Input;
use Redirect;

class Statistics extends Base
{
	protected $viewPath = 'admin.statistics';

	public function index()
	{

        return $this->render('index', [

        ]);
	}
}