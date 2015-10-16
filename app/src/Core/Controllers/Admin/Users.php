<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\Core\Models\Role;
use App\Core\Models\User;
use App\Haku\Searchers\Users as UsersSearcher;
use Auth;
use Confide;
use Config;
use Input;
use Lomake;
use Mail;
use Redirect;
use Session;
use View;

class Users extends Base
{
    use \CRUD;
    protected $viewPath = 'admin.users';

    protected $crudOptions = [
        'modelClass'  => 'App\Core\Models\User',
        'prefetch'    => ['business', 'roles'],
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'user',
        'actionsView' => 'admin.users.actions',
        'bulkActions' => ['activate', 'deactivate'],
        'indexFields' => ['business_name', 'email', 'payments', 'activation'],
        'presenters'  => [
            'business_name' => ['App\Core\Controllers\Admin\Users', 'presentBusinessName'],
            'activation'    => ['App\Core\Controllers\Admin\Users', 'presentActivation'],
            'payments'      => ['App\Core\Controllers\Admin\Users', 'presentPayments'],
        ]
    ];

    //--------------------------------------------------------------------------
    // PRESENTERS
    //--------------------------------------------------------------------------
    public static function presentBusinessName($value, $item)
    {
        if ($item->business !== null) {
            return View::make('admin.users.el.business_name', [
                'business' => $item->business
            ]);
        }

        return $value;
    }

    public static function presentActivation($value, $item)
    {
        if ($item->hasRole(Role::CONSUMER)) {
            return '';
        }

        if (!empty($item->business) && $item->business->is_activated) {
            return '<span class="label label-success">'.trans('common.yes').'</span>';
        }

        return '<span class="label label-danger">'.trans('common.no').'</span>';
    }

    /**
     * Show controls to send/display related payments of a business
     *
     * @param string               $value
     * @param App\Core\Models\User $user
     *
     * @return View|string
     */
    public static function presentPayments($value, $user)
    {
        return View::make('admin.users.el.payments', [
            'user' => $user
        ]);
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------

    /**
     * @{@inheritdoc}
     */
    public function index()
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

        // Don't know why model User doesn't take SoftDeleteTrait, so this
        // would fix temporarily.
        $query = $query->whereNull('deleted_at');

        // Show only Business user
        $query = $query->whereHas('roles', function ($query) {
            return $query->where('name', Role::BUSINESS);
        });

        // Pagination please
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $query->paginate($perPage);

        return $this->renderList($items);
    }

    /**
     * Overwrite upsert behavior
     *
     * @param View     $view
     * @param Eloquent $user
     *
     * @return View
     */
    public function overwrittenUpsert($view, $user)
    {
        // @TODO: DUPLICATED WITH Core\Controllers\User@profile
        // Additional data to be passed to View
        $data = [];
        $business = $user->business ?: new Business();
        $data['business'] = $business;

        $isAdmin = Confide::user()->is_admin || Session::has('stealthMode');
        $businessLomake = Lomake::make($business, [
            'route'             => ['admin.users.business', $user->id],
            'langPrefix'        => 'user.business',
            'fields'            => [
                'description'      => ['type' => 'html_multilang', 'default' => $business->description_html],
                'size'             => ['type' => false],
                'lat'              => ['type' => false],
                'lng'              => ['type' => false],
                'note'             => ['type' => false],
                'payment_options'  => ['type' => false],
                'payment_methods'  => ['type' => false],
                'deposit_rate'     => ['type' => false],
                'disabled_payment' => ['type' => false],
                'bank_account'     => ['type' => 'text', 'hidden' => !$isAdmin],
                'meta_title'       => ['type' => 'text_multilang', 'hidden' => !$isAdmin],
                'meta_description' => ['type' => 'text_multilang', 'hidden' => !$isAdmin],
                'meta_keywords'    => ['type' => 'text_multilang', 'hidden' => !$isAdmin],
                'is_hidden'        => ['type' => 'radio', 'hidden' => !$isAdmin],
            ],
        ]);

        // Get all business categories
        $data['categories'] = BusinessCategory::getAll();
        // Selected business categories
        $selectedCategories = $business->businessCategories->lists('id');
        $data['selectedCategories'] = $selectedCategories;

        // Attach the form
        $data['businessLomake'] = $businessLomake;

        // Get all modules in the system
        $data['modules'] = Config::get('varaa.premium_modules');

        $view->with($data);

        return $view;
    }

    /**
     * @{@inheritdoc}
     */
    public function upsertHandler($user)
    {
        $user->fill(Input::all());
        if (Input::has('password') && Input::has('password_confirmation')) {
            $user->password              = Input::get('password');
            $user->password_confirmation = Input::get('password_confirmation');
        }
        // New user, so what should we do
        if ($user->id === null) {
            $result = $user->save();

            // Duplicated or something
            if ($result === false) {
                return Redirect::route(
                    static::$crudRoutes['upsert'],
                    ['id' => $user->id]
                )->withErrors($user->errors(), 'top');
            }

            // Assign to group Business
            $role = Role::user();
            $user->attachRole($role);

            // Auto-confirm
            if (Input::has('auto_confirm')) {
                Confide::confirm($user->confirmation_code);
            }

            // Create business entry
            $business = new Business([
                'name'  => Input::get('business_name'),
                'phone' => Input::get('business_phone'),
            ]);
            $business->user()->associate($user);
            try {
                $business->saveOrFail();
            } catch (\Watson\Validating\ValidationException $ex) {
                $business->forceSave();
            }

            // Send notification email
            Mail::send(
                'admin.users.emails.created',
                ['password' => Input::get('password')],
                function ($message) {
                    $message
                    ->to(Input::get('email'))
                    ->subject(trans('user.password_reminder.created.heading'));
                }
            );

            // Redirect to edit page, so that we can enter business information
            return Redirect::route(
                static::$crudRoutes['upsert'],
                ['id' => $user->id]
            );
        } else {
            $user->updateUniques();

            if (Input::has('password')) {
                // Send notification email
                Mail::send(
                    'admin.users.emails.reset',
                    ['password' => Input::get('password')],
                    function ($message) {
                        $message
                        ->to(Input::get('email'))
                        ->subject(trans('user.password_reminder.reset.heading'));
                    }
                );
            }
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function doEdit($type, $id = null)
    {
        // Too bad now $type has the model ID
        $id = $type;
        $type = 'users';

        try {
            $item = $this->model->where('id', $id)->firstOrFail();

            $input = Input::all();
            unset($input['_token']);

            $item->unguard();
            $item->fill($input);
            $item->reguard();

            $item->updateUniques();
        } catch (\Exception $ex) {
            return Redirect::back()
                ->withInput()
                ->withErrors($this->errorMessageBag($ex->getMessage()), 'top');
        }

        return Redirect::route('admin.crud.index', ['model' => $type]);
    }

    /**
     * Login as a user
     *
     * @param int $id
     *
     * @return Redirect
     */
    public function stealSession($id)
    {
        if (Confide::user()->hasRole('Admin') ||
            Session::get('stealthMode') !== null) {
            Auth::loginUsingId($id);

            // Also dump data to session for Service usage
            Auth::user()->dumpToSession();

            // No reset value of `stealthMode` since the first one is the
            // genuine admin
            if (Session::has('stealthMode') === false) {
                Session::set('stealthMode', Confide::user()->id);
            }
        }

        return Redirect::route('home');
    }

    /**
     * Enable a service module of a user
     *
     * @param int $id User ID
     *
     * @return Redirect
     */
    public function enableModule($id)
    {
        $user =  User::findOrFail($id);
        try {
            $user->updateDisabledModules(Input::get('modules', []));
        } catch (\Illuminate\Database\QueryException $ex) {
            // Silently failed
        }

        return Redirect::back()
            ->with('messages', $this->successMessageBag(
                trans('admin.modules.success_enabled', [
                    'module' => implode(', ', Input::get('modules', [])),
                    'user'   => $user->username
                ])
            ));
    }

    /**
     * Toggle activation of a module
     *
     * @param int $userId
     * @param int $id     ID in the pivot table
     *
     * @return Redirect
     */
    public function toggleActivation($userId, $id)
    {
        try {
            $result = Module::toggleActivation($id);

            return Redirect::route('admin.users.modules', ['id' => $userId])
                ->with('messages', $this->successMessageBag(
                    trans('admin.modules.success_activation')
                ));
        } catch (\Exception $ex) {
            $errors = $this->errorMessageBag(trans('common.err.unexpected'));

            return Redirect::back()->withErrors($errors);
        }
    }

    /**
     * Update business information of a user
     *
     * @param int $id
     *
     * @return Redirect
     */
    public function updateBusiness($id)
    {
        $errors = null;
        $user = User::findOrFail($id);
        $business = $user->business !== null
            ? $user->business
            : new Business();

        try {
            $business->updateInformation(Input::all(), $user);
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        } catch (\Exception $ex) {
            $errors = $this->errorMessageBag($ex->getMessage());
        }

        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    /**
     * Activate a list of business users
     *
     * @param array $ids
     *
     * @return void
     */
    protected function activate($ids)
    {
        Business::whereIn('user_id', $ids)->update(['is_activated' => true]);
    }

    /**
     * Deactivate a list of business users
     *
     * @param array $ids
     *
     * @return void
     */
    protected function deactivate($ids)
    {
        Business::whereIn('user_id', $ids)->update(['is_activated' => false]);
    }

    /**
     * Show the list of deleted users
     *
     * @return View
     */
    public function deleted()
    {
        // We'll add a new option to bulk restore deleted users
        $this->crudOptions['bulkActions'] = array_merge(
            $this->crudOptions['bulkActions'],
            ['restore']
        );

        // Show only business name and email
        $this->crudOptions['indexFields'] = ['business_name', 'email'];
        $users = User::onlyTrashed()->orderBy('updated_at', 'ASC')->paginate(Config::get('view.perPage'));

        return $this->renderList($users);
    }

    /**
     * Restore a list of deleted users
     *
     * @param array $ids
     *
     * @return void
     */
    protected function restore($ids)
    {
        $users = User::onlyTrashed()->whereIn('id', $ids)->get();
        foreach ($users as $user) {
            $user->restore();
        }
    }

    /**
     * Overwritten default method to use new Haku search
     *
     * @return View
     */
    public function search()
    {
        $keyword = Input::get('q');
        $searcher = new UsersSearcher(['keyword' => $keyword]);
        $items = $searcher->search();

        // Disable sorting items
        $this->crudSortable = false;

        return $this->renderList($items);
    }
}
