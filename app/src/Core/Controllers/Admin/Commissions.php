<?php namespace App\Core\Controllers\Admin;

use Input, Response, Log, Settings;
use App\Core\Models\CommissionLog;
use App\Core\Models\User;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use Carbon\Carbon;

class Commissions extends Base
{
    protected $viewPath = 'admin.commissions';

    /**
     * Show the modal to enter user commission
     *
     * @param int    $userId
     * @param string $action
     *
     * @return View
     */
    public function show($userId, $action)
    {
        return $this->render('modal', [
            'userId' => $userId,
            'action' => $action
        ]);
    }

    /**
     * Add/substract a new commission to user
     *
     * @param int    $userId
     * @param string $action
     *
     * @return Response
     */
    public function doAction($userId, $action)
    {
        $user = User::findOrFail($userId);

        try {
            $input = Input::all();
            $input['action'] = $action;

            $item = new CommissionLog($input);
            $item->user()->associate($user);
            $item->saveOrFail();

            return Response::json(['message' => trans('admin.commissions.done')]);
        } catch (\Exception $ex) {
            Log::warning($ex->getMessage(), [
                'context' => 'admin.users.commissions',
                'user' => $userId
            ]);

            return Response::json(['message' => trans('admin.commissions.fail')], 500);
        }
    }

    /**
     * Show all commissions of a single user
     *
     * @param int $userId
     *
     * @return view
     */
    public function index($userId)
    {
        $user = User::findOrFail($userId);

        return $this->render('index', [
            'commissions' => $user->commissions()->latest()->get()
        ]);
    }

    /**
     * Show all employee commissions of a single user
     *
     * @param int $userId
     *
     * @return view
     */
    public function counter($userId)
    {
        $current = Carbon::now();
        $langPrefix = 'admin.commissions';

        if (!empty($date)) {
            try {
                $current = Carbon::createFromFormat('Y-m-d', $date . '-01');
            } catch (\Exception $ex) {
                $current = Carbon::now();
            }
        }

        $startOfMonth    = $current->startOfMonth()->toDateString();
        $endOfMonth      = $current->endOfMonth()->toDateString();

        $user = User::findOrFail($userId);

        $bookings = Booking::getBookingsByEmployeeStatus(
            $userId,
            Employee::STATUS_EMPLOYEE,
            $startOfMonth,
            $endOfMonth
        );

        $fields = [
            'date', 'name', 'price', 'commission', 'booking_status', 'notes'
        ];

        $commissionRate = Settings::get('commission_rate');
        $currencySymbol = Settings::get('currency');

        return $this->render('counter', [
            'items'          => $bookings,
            'fields'         => $fields,
            'langPrefix'     => $langPrefix,
            'user'           => $user,
            'commissionRate' => $commissionRate,
            'currencySymbol' => $currencySymbol
        ]);
    }
}
