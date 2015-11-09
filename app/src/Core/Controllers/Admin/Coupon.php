<?php namespace App\Core\Controllers\Admin;
use App;
use App\Core\Models\Setting;
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
	protected $viewPath = 'admin';

    protected $customViewPath = [
        'index' => 'admin.coupon.campaigns'
    ];

    protected $crudOptions = [
        'modelClass'  => 'App\Core\Models\Campaign',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'admin.coupon.campaign',
        'indexFields' => ['name', 'discount_type', 'discount','reusable_code']
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

    public function save()
    {
        $key   = 'coupon';
        $value = Input::get('coupon');

        $setting = Setting::findOrNew($key);
        $setting->key   = $key;
        $setting->value = $value;
        $setting->save();

        return Redirect::route('admin.coupon.index');
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);

        $view = ($campaign->isReusable) ? 'reuseable' : 'disposable';

        return $this->render('coupon.upsert', [ 
            'campaign' => $campaign,
            'today' => Carbon::today(),
            'route' => ['admin.coupon.campaigns.doEdit', $id],
            'discountType' => [
                'percentage' => '%',
                'amount'     => '&euro;'
            ]
        ]);
    }

    public function stats($id)
    {
        $campaign = Campaign::findOrFail($id);

        $view = ($campaign->isReusable) ? 'reuseable' : 'disposable';

        return $this->render('coupon.' . $view , [ 
            'campaign' => $campaign,
            'today' => Carbon::today(),
            'id' => $id,
        ]);
    }

    public function export($id)
    {
        $campaign = Campaign::findOrFail($id);

        $output = sprintf("%s;%s\n", trans('admin.coupon.code'), trans('admin.coupon.is_used'));
        foreach ($campaign->coupons as $coupon) {
            $output .= sprintf("%s;%s\n", $coupon->code, trans('admin.coupon.campaign.' . $coupon->isUsed));
        }

        $name = sprintf('campaign_%d_coupons.csv', $id);
        $file = public_path() . '/tmp/' . $name;

        file_put_contents($file, $output);

        return Response::download($file, $name, ['content-type' => 'text/cvs']);
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
    	
    	return $this->render('coupon.upsert', [ 
    		'campaign' => $campaign,
            'today' => Carbon::today(),
            'route' => ['admin.coupon.campaigns.create', (isset($campaign->id)) ? $campaign->id: null],
            'discountType' => [
                'percentage' => '%',
                'amount'     => '&euro;'
            ]
    	]);
    }

    public function doCreate()
    {
        $campaign = new Campaign();
        $isReusable = (int) Input::get('is_reusable');
        $reusableCode =  Input::get('reusable_code');

           
        if ($isReusable === 1) {
            $validator  = $campaign->getResuableCodeValidator();

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator->errors());
            }

            $isExisted = Campaign::where('reusable_code', '=', $reusableCode)->first();
            if ( ! empty($isExisted)) {
                return Redirect::back()->withInput()->withErrors([
                    'duplicated_code' => trans('admin.coupon.campaign.errors.duplicated_code')]
                );
            }
        }

        try {
            $data = Input::all();

            if (!empty(Input::get('begin_at'))) {
                $data['begin_at']  = carbon_date(Input::get('begin_at'));
            }
            
            if (!empty(Input::get('expire_at'))) {
                $data['expire_at'] = carbon_date(Input::get('expire_at'));
            }

            $campaign->fill($data);
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

    public function doEdit($id)
    {
        try {

            $campaign          = Campaign::findOrFail($id);
            $data['name']      = Input::get('name');
            $data['begin_at']  = carbon_date(Input::get('begin_at'));
            $data['expire_at'] = carbon_date(Input::get('expire_at'));    
            $campaign->fill($data);
            $campaign->saveOrFail();

        } catch(\Watson\Validating\ValidationException $ex){
            return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }

        return Redirect::route('admin.coupon.campaigns');
    }

   /**
     * Delete a category
     *
     * @param int $id
     *
     * @return Redirect
     */
    public function delete($id)
    {
        $item = Campaign::findOrFail($id);

        if ( ! empty($item->id)) {
            foreach ($item->coupons as $coupon) {
                $coupon->delete();
            }
        }

        $item->delete();

        if (Request::ajax() === true) {
            return Response::json(['success' => true]);
        }

        $referer = Request::instance()->header('referer');

        return Redirect::to(!empty($referer) ? $referer : static::$crudRoutes['index'])
            ->with('messages',
                $this->successMessageBag(trans('olut::olut.success_delete')));
    }
}
