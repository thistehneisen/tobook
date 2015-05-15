<?php namespace App\Core\Controllers\Admin;

use Input, Response, Log, Settings, Config, Util;
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
    public function counter($userId, $employeeId = null)
    {
        $current = Carbon::now();
        $langPrefix = 'admin.commissions';
        $date = Input::get('date');

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
        $status = (empty($employeeId))
            ? Employee::STATUS_EMPLOYEE
            : Employee::STATUS_FREELANCER;

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));

        $bookings = Booking::getBookingsByEmployeeStatus(
            $userId,
            $status,
            $employeeId,
            $perPage,
            $startOfMonth,
            $endOfMonth
        );

        $fields = [
            'date', 'name', 'price', 'commission', 'booking_status', 'notes'
        ];

        $months = Util::getMonthsSelection($current);

        $freelancers = $user->asEmployees()
            ->where('status', '=', Employee::STATUS_FREELANCER)
            ->get();

        $needToPay = 0;
        $paid      = 0;
        $pending   = $needToPay - $paid;

        $commissionRate = Settings::get('commission_rate');
        $currencySymbol = Settings::get('currency');

        return $this->render('counter', [
            'items'          => $bookings,
            'fields'         => $fields,
            'months'         => $months,
            'date'           => $current->format('Y-m'),
            'langPrefix'     => $langPrefix,
            'current'        => $current,
            'user'           => $user,
            'freelancers'    => $freelancers,
            'employeeId'     => $employeeId,
            'paid'           => $paid,
            'pending'        => $pending,
            'commissionRate' => $commissionRate,
            'currencySymbol' => $currencySymbol
        ]);
    }
}
