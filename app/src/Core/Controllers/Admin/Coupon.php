<?php namespace App\Core\Controllers\Admin;
use App;
use App\Core\Settings;
use App\Core\Models\Campaign;
use App\Lomake\FieldFactory;
use Config;
use Input;
use Redirect;
use Lomake;
use Carbon\Carbon;

class Coupon extends Base
{
	protected $viewPath = 'admin';

    public function index()
    {
    	$definition = Config::get('varaa.settings.coupon');
        $definition['name'] = $name = 'coupon';
        $definition['default'] = isset($definition['default'])
            ? $definition['default']
            : '';

        // Overwrite with settings in database
        $definition['default'] = \Settings::get($name) !== null
            ? \Settings::get($name)
            : $definition['default'];

        $control = FieldFactory::create($definition);

        return $this->render('coupon.index', [
        	'control' => $control
        ]);
    }

    public function setting()
    {
    	return;
    }

    public function campaigns()
    {
    	return $this->render('coupon.campaigns', []);
    }

    public function create()
    {
    	$campaign = new Campaign;
    	
    	return $this->render('coupon.create', [ 
    		'campaign' => $campaign,
            'today' => Carbon::today(),
    	]);
    }

    public function doCreate()
    {

    }
}
