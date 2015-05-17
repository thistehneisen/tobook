<?php namespace App\Core\Controllers\Admin;

use Input, Response, Log, Settings, Config, Util, Redirect, App, Mail;
use App\Core\Models\CommissionLog;
use App\Core\Models\BusinessCommission;
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
            'created_at', 'name', 'price', 'commission_status', 'booking_status', 'notes'
        ];

        $months = Util::getMonthsSelection($current);

        $freelancers = $user->asEmployees()
            ->where('status', '=', Employee::STATUS_FREELANCER)
            ->get();

        $needToPay = Booking::countCommissionNeedToPay(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $paid = Booking::countCommissionPaid(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $commissionRate = Settings::get('commission_rate');
        $currencySymbol = Settings::get('currency');


        $pending   = $needToPay->total * $commissionRate;

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

    /**
     * Change status of an booking commission
     * @return Redirect
     */
    public function status($userId, $bookingId)
    {
        $status = Input::get('status');
        $validStatuses = [
            BusinessCommission::STATUS_SUSPEND,
            BusinessCommission::STATUS_PAID,
            BusinessCommission::STATUS_CANCELLED
        ];

        if (in_array($status, $validStatuses)){
            $this->changeStatus($userId, $bookingId, $status);
        }
        return Redirect::back();
    }

    public function massStatus($userId)
    {
        $status = Input::get('status');
        $ids = Input::get('ids');

        $validStatuses = [
            BusinessCommission::STATUS_SUSPEND,
            BusinessCommission::STATUS_PAID,
            BusinessCommission::STATUS_CANCELLED
        ];

        if (in_array($status, $validStatuses)){
            foreach ($ids as $bookingId) {
                $this->changeStatus($userId, $bookingId, $status);
            }
        }
        return Redirect::back();
    }

    private function changeStatus($userId, $bookingId, $status)
    {
        $booking            = Booking::findOrFail($bookingId);
        $user               = User::find($userId);
        $businessCommission = BusinessCommission::where('booking_id', $bookingId)->first();

        $commissionRate     = Settings::get('commission_rate');
        $amount             = $booking->total_price * $commissionRate;

        if (empty($businessCommission)) {
            $businessCommission = new BusinessCommission();
        }

        $businessCommission->fill([
            'status' => $status,
            'amount' => $amount,
        ]);

        $businessCommission->user()->associate($user);
        $businessCommission->booking()->associate($booking);
        $businessCommission->save();
    }

    private function report($userId, $employeeId)
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
        $perPage = null;

        $employeeBookings = Booking::getBookingCommisions(
            $userId,
            Employee::STATUS_EMPLOYEE,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $freelancers = $user->asEmployees()
            ->where('status', '=', Employee::STATUS_FREELANCER)
            ->get();

        $freelancersBookings = [];

        foreach ($freelancers as $freelancer) {
            $freelancersBookings[$freelancer->name] = Booking::getBookingCommisions(
                $userId,
                Employee::STATUS_FREELANCER,
                $freelancer->id,
                $startOfMonth,
                $endOfMonth
            );
        }


        $fields = [
            'created_at','date', 'employee', 'customer', 'price', 'booking_status', 'notes'
        ];

        $needToPay = Booking::countCommissionNeedToPay(
            $userId,
            null,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $paid = Booking::countCommissionPaid(
            $userId,
            null,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $commissionRate = Settings::get('commission_rate');
        $currencySymbol = Settings::get('currency');


        $pending   = $needToPay->total * $commissionRate;

        return [
            'employeeBookings'    => $employeeBookings,
            'freelancersBookings' => $freelancersBookings,
            'fields'              => $fields,
            'date'                => $current->format('Y-m'),
            'langPrefix'          => $langPrefix,
            'current'             => $current,
            'user'                => $user,
            'employeeId'          => $employeeId,
            'paid'                => $paid,
            'pending'             => $pending,
            'commissionRate'      => $commissionRate,
            'currencySymbol'      => $currencySymbol
        ];
    }

    public function pdf($userId, $employeeId = null)
    {
        $data = $this->report($userId, $employeeId);
        return $this->render('pdf', $data);
    }

    public function sendReport($userId, $employeeId = null)
    {
        $data = $this->report($userId, $employeeId);
        $html = $this->render('pdf', $data)->render();

        $current = $data['current'];
        $filename = public_path() . '/tmp/' . 'report_' . $userId . '_' . $current->format('YmdHis') . '.pdf';

        $pdf = App::make('dompdf');
        $pdf->loadHTML($html);
        $pdf->save($filename);

        $address = Input::get('email_address');
        $subject = Input::get('email_title');
        $content = Input::get('email_content');

        Mail::send('admin.commissions.mail', [
            'title' => $subject,
            'body' => nl2br($content)
        ], function($message) use ($address, $filename, $subject) {
            $message->to($address)->subject($subject);
            $message->attach($filename, array('mime' => "application/pdf"));
        });
        // unlink($filename);
        return Redirect::back();
    }
}
