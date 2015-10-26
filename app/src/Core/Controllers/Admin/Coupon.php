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
    use \CRUD;
	protected $viewPath = 'admin.coupon';

    protected $customViewPath = [
        'index' => 'admin.coupon.campaigns'
    ];

    protected $crudOptions = [
        'modelClass'  => 'App\Core\Models\Campaign',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'admin.coupon.campaign',
        'indexFields' => ['name']
    ];

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

    public function edit()
    {
        
    }

    public function campaigns()
    {
        // To make sure that we only show records of current user
        $query = $this->getModel();

        // Allow to filter results in query string
        $query = $this->applyQueryStringFilter($query);

        // If this controller is sortable
        if ($this->getOlutOptions('sortable') === true) {
            $query = $query->orderBy('order');
        }

        // Eager loading
        if ($prefetch = $this->getOlutOptions('prefetch')) {
            $query = $query->with($prefetch);
        }

        $query = $this->oulutCustomIndexQuery($query);

        // Pagination please
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $perPage = $perPage < 0 ? 0 : $perPage;
        $items = $query->paginate($perPage);

        return $this->renderList($items);
    }

    public function create()
    {
    	$campaign = new Campaign;
    	
    	return $this->render('coupon.create', [ 
    		'campaign' => $campaign,
            'today' => Carbon::today(),
            'discountType' => [
                'percentage' => '%',
                'amount'     => '&euro;'
            ]
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

        return Redirect::route('admin.coupon.campaigns');
    }
}
