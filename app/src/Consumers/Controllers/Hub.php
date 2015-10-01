<?php namespace App\Consumers\Controllers;

use App\Consumers\Models\Consumer;
use App\Consumers\Models\Group;
use App\Core\Controllers\Base;
use App, Confide, DB, Input, Lang, Redirect, View;
use Session, Entrust;

class Hub extends Base
{
    use \CRUD;
    use \App\Consumers\Traits\Marketing;

    protected $viewPath = 'modules.co';
    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\Consumer',
        'langPrefix' => 'co',
        'indexFields' => ['first_name', 'last_name', 'email', 'phone', 'receive_email', 'receive_sms', 'services'],
        'presenters' => [
            'receive_email' => ['App\Olut\Presenters\Bool', 'render'],
            'receive_sms' => ['App\Olut\Presenters\Bool', 'render'],
            'services' => ['App\Consumers\Controllers\Hub', 'presentServices']
        ],
        'lomake' => [
            'receive_email' => [
                'type' => 'radio',
                'default' => 1,
            ],
            'receive_sms' => [
                'type' => 'radio',
                'default' => 1,
            ],
        ],
        'layout' => 'modules.co.layout',
        'bulkActions' => [
            'group',
            'send_email',
            'send_sms',
            'send_all_email',
            'send_all_sms',
            'destroy',
        ],
        'bulkActionsTobook' => [
            'group',
            'destroy',
        ],
    ];

    /**
     * Return options defined in $this->crudOptions
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function getOlutOptions($key, $default = null)
    {
        // If environment is toobok and the user is not admin
        if (App::environment() === 'tobook' && $key === 'bulkActions' 
            && !Entrust::hasRole('Admin') && Session::get('stealthMode') === null) {
            if(!$this->user->isAdmin) {
                return $this->crudOptions[$key. 'Tobook'];
            }
        }

        if (isset($this->crudOptions) && isset($this->crudOptions[$key])) {
            return $this->crudOptions[$key];
        } else {
            return $default;
        }
    }

    public static function presentServices($value, $item)
    {
        $str = '<ul class="list-unstyle">';
        foreach ($item->getServiceAttribute() as $key => $value) {
            $str .= '<li><a class="js-showHistory" href="' . route('consumer-hub.history') . '" data-consumerid="' . $item->id . '" data-service="' . $key . '">' . $value . '</a></li>';
        }
        $str .= '</ul>';

        return $str;
    }

    /**
     * @{@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        if ($item->id) {
            $item->fill(Input::all());
            $item->saveOrFail();
            $item->users()->detach($this->user->id);
            $item->users()->attach($this->user);
        } else {
            $item = Consumer::make(Input::all());
        }

        return $item;
    }

    /**
     * @{@inheritdoc}
     */
    public function oulutCustomIndexQuery($query)
    {
        return $query->orderBy('first_name', 'ASC')
            ->orderBy('last_name', 'ASC');
    }

    public function getHistory()
    {
        $consumerId = Input::get('id');
        $service = Input::get('service');
        $history = [];

        switch ($service) {
            case 'as':
                $history = DB::table('as_bookings')
                    ->join('as_booking_services', 'as_bookings.id', '=', 'as_booking_services.booking_id')
                    ->join('as_services', 'as_booking_services.service_id', '=', 'as_services.id')
                    ->join('as_employees', 'as_booking_services.employee_id', '=', 'as_employees.id')
                    ->where('as_bookings.user_id', Confide::user()->id)
                    ->where('as_bookings.consumer_id', $consumerId)
                    ->select('as_bookings.id', 'as_employees.name as employee_name', 'as_bookings.uuid', 'as_booking_services.date', 'as_bookings.start_at', 'as_bookings.end_at', 'as_services.name', 'as_bookings.notes', 'as_bookings.created_at')
                    ->distinct()
                    ->orderBy('as_bookings.date', 'DESC')
                    ->take(10)
                    ->get();
                break;

            case 'lc':
                $baseHistory = DB::table('lc_transactions')
                    ->join('businesses', 'lc_transactions.user_id', '=', 'businesses.user_id')
                    ->join('lc_consumers', 'lc_transactions.consumer_id', '=', 'lc_consumers.id')
                    ->where('offer_id', null)
                    ->where('voucher_id', null)
                    ->where('lc_transactions.user_id', Confide::user()->id)
                    ->where('lc_consumers.consumer_id', $consumerId)
                    ->select('lc_transactions.created_at', 'lc_transactions.offer_id', 'lc_transactions.voucher_id', 'lc_transactions.point', 'businesses.name as business_name')
                    ->take(10)
                    ->get();

                $offerHistory = DB::table('lc_transactions')
                    ->join('businesses', 'lc_transactions.user_id', '=', 'businesses.user_id')
                    ->join('lc_consumers', 'lc_transactions.consumer_id', '=', 'lc_consumers.id')
                    ->join('lc_offers', 'lc_transactions.offer_id', '=', 'lc_offers.id')
                    ->where('lc_transactions.user_id', Confide::user()->id)
                    ->where('lc_consumers.consumer_id', $consumerId)
                    ->select('lc_transactions.created_at', 'lc_transactions.offer_id', 'lc_transactions.voucher_id', 'lc_offers.name', 'lc_transactions.stamp', 'businesses.name as business_name')
                    ->take(10)
                    ->get();

                $voucherHistory = DB::table('lc_transactions')
                    ->join('businesses', 'lc_transactions.user_id', '=', 'businesses.user_id')
                    ->join('lc_consumers', 'lc_transactions.consumer_id', '=', 'lc_consumers.id')
                    ->join('lc_vouchers', 'lc_transactions.voucher_id', '=', 'lc_vouchers.id')
                    ->where('lc_transactions.user_id', Confide::user()->id)
                    ->where('lc_consumers.consumer_id', $consumerId)
                    ->select('lc_transactions.created_at', 'lc_transactions.offer_id', 'lc_transactions.voucher_id', 'lc_vouchers.name', 'lc_transactions.point', 'businesses.name as business_name')
                    ->take(10)
                    ->get();

                $history = array_merge($baseHistory, $offerHistory, $voucherHistory);

                break;
        }

        usort($history, function ($a, $b) {
            return strcmp($b->created_at, $a->created_at);
        });

        return View::make('modules.co.history', [
            'service' => $service,
            'history' => $history,
        ]);
    }

    public function import()
    {
        return View::make('modules.co.import');
    }

    public function doImport()
    {
        if (!Input::hasFile('upload')) {
            return Redirect::back()
                ->withErrors(['upload' => trans('co.import.upload_is_missing')]);
        }

        $upload = Input::file('upload');
        if (!$upload->isValid()) {
            return Redirect::back()
                ->withErrors(['upload' => trans('co.import.upload_is_invalid')]);
        }

        try {
            // this may have issue with large file!!!
            // consider using this http://www.maatwebsite.nl/laravel-excel/docs maybe?
            // $csvLines = file($upload->getRealPath());
            $csv = file_get_contents($upload->getRealPath());
            $csvLines = preg_split('#(\r|\n)#', $csv, -1, PREG_SPLIT_NO_EMPTY);

            $importResults = Consumer::importCsv($csvLines, $this->user);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['upload' => $e->getMessage()]);
        }

        $messages = [];
        $errors = [];
        $successCount = 0;
        $errorCount = 0;
        foreach ($importResults as $result) {
            if ($result['success']) {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = trans('co.import.save_error_row_x_y', [
                    'row' => $result['row'],
                    'error' => $result['error'],
                ]);
            }
        }

        if ($successCount) {
            array_unshift($messages, Lang::choice('co.import.imported_x', $successCount, ['count' => $successCount]));
            $messageBag = $this->successMessageBag($messages);
        }

        if ($errorCount) {
            $errorBag = $this->errorMessageBag($errors);
        }


        $redirect = Redirect::to(route('consumer-hub.import'));

        if ($successCount) {
            $redirect->with('messages', $messageBag);
        }

        if ($errorCount) {
            $redirect->withErrors($errorBag);
        }

        return $redirect;

    }

    public function bulkGroup($ids)
    {
        $consumers = Consumer::ofCurrentUser()->whereIn('id', $ids)->get();

        $group = null;
        $groupId = intval(Input::get('group_id'));
        if (!empty($groupId)) {
            $group = Group::ofCurrentUser()->findOrFail($groupId);
        } else {
            $newGroupName = Input::get('new_group_name');

            if (!empty($newGroupName)) {
                $group = new Group([
                    'name' => $newGroupName,
                ]);
                $group->user()->associate($this->user);
                $group->saveOrFail();
            }
        }

        if (!empty($group)) {
            foreach ($consumers as $consumer) {
                $group->consumers()->attach($consumer->id);
            }

            return Redirect::to(route('consumer-hub.groups.index'));
        }

        $groups = Group::ofCurrentUser()->get();
        $groupPairs = [];
        foreach ($groups as $group) {
            $groupPairs[trans('co.groups.existing_group')][$group->id] = $group->name;
        }
        $groupPairs[0] = trans('co.groups.new_group');

        return View::make('modules.co.bulk_group', [
            'groupPairs' => $groupPairs,
            'consumers' => $consumers,
        ]);
    }

    public function bulkSendEmail($ids)
    {
        $result = static::sendEmails($ids);
        if (array_key_exists('template_id', $result)) {
            return Redirect::route('consumer-hub.history.email', ['campaign_id' => $result['template_id']])
                ->with('messages', $this->successMessageBag(
                    trans('co.email_templates.sent_to_x_of_y', $result)
                ));
        }

        return View::make('modules.co.bulk_send_email', $result);
    }

    public function bulkSendSms($ids)
    {
        $result = static::sendSms($ids);
        if (array_key_exists('template_id', $result)) {
            return Redirect::route('consumer-hub.history.sms', ['sms_id' => $result['template_id']])
                ->with('messages', $this->successMessageBag(
                    trans('co.sms_templates.sent_to_x_of_y', $result)
                ));
        }

        return View::make('modules.co.bulk_send_sms', $result);
    }

    public function bulkSendAllEmail($ids)
    {
        return static::bulkSendEmail($ids);
    }

    public function bulkSendAllSms($ids)
    {
        return static::bulkSendSms($ids);
    }
}
