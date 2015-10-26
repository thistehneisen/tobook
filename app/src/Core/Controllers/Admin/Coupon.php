<?php namespace App\Core\Controllers\Admin;
use App;
use App\Core\Settings;
use App\Core\Models\Campaign;
use App\Lomake\FieldFactory;
use Illuminate\Support\ViewErrorBag;
use Config;
use Input;
use Lomake;
use Carbon\Carbon;
use Validator;
use Redirect;
use Request;
use Response;


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
        $campaign = new Campaign();
        $isReusable = (boolean) Input::get('is_reusable');

        if ($isReusable) {
            $validator  = $campaign->getResuableCodeValidator();
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator->errors());
            }
        }

        try {
            $campaign->fill(Input::all());
            $campaign->saveOrFail();
            
            if (! $isReusable) {
                $campaign->makeCoupons();
            } else {
                $campaign->makeCoupon($campaign->reusable_code);
            }

        } catch(\Watson\Validating\ValidationException $ex){
            return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }

    }
}
