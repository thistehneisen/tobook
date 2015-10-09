<?php namespace App\Core\Controllers\Admin;

use Input, Response, Log, Settings, Config, Util, Redirect, App, Mail;
use App\Core\Models\CommissionLog;
use App\Core\Models\BusinessCommission;
use App\Core\Models\Commission\Counter;
use App\Core\Models\Commission\ToBookCounter;
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
        $current    = Carbon::now();
        $langPrefix = 'admin.commissions';
        $date       = Input::get('date');
        $perPage    = (int) Input::get('perPage', Config::get('view.perPage'));

        $counter = new Counter();
        if(App::environment() === 'tobook' || Config::get('varaa.commission_style') === 'tobook') {
            $counter = new ToBookCounter();
        }

        $data = $counter->counterData(
            $current,
            $perPage,
            $langPrefix,
            $date,
            $userId,
            $employeeId
        );

        return $this->render('counter', $data);
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
            $this->changeStatus($bookingId, $status);
        }
        return Redirect::back();
    }

    public function massStatus($userId)
    {
        $status = Input::get('status');
        $ids    = Input::get('ids');

        //Redirect back when no row or status is selected
        if (empty($ids) || empty($status)) {
            return Redirect::back();
        }

        $validStatuses = [
            BusinessCommission::STATUS_SUSPEND,
            BusinessCommission::STATUS_PAID,
            BusinessCommission::STATUS_CANCELLED
        ];

        if (in_array($status, $validStatuses)){
            foreach ($ids as $bookingId) {
                $this->changeStatus($bookingId, $status);
            }
        }
        return Redirect::back();
    }

    private function changeStatus($bookingId, $status)
    {
        $businessCommission = BusinessCommission::where('booking_id', $bookingId)->first();
        $businessCommission->status = $status;
        $businessCommission->save();
    }

    private function report($userId, $employeeId = null)
    {
        $current    = Carbon::now();
        $langPrefix = 'admin.commissions';
        $date       = Input::get('date');

        $counter = new Counter();
        if(App::environment() === 'tobook' || Config::get('varaa.commission_style') === 'tobook') {
            $counter = new ToBookCounter();
        }

        $data = $counter->reportData(
            $current,
            $langPrefix,
            $date,
            $userId,
            $employeeId
        );

        return $data;
    }

    public function pdf($userId, $employeeId = null)
    {
        $data = $this->report($userId, $employeeId);
        return $this->render('pdf', $data);
    }

    public function sendReport($userId)
    {
        $employeeId = Input::get('employee');
        $data = $this->report($userId, $employeeId);
        $html = $this->render('pdf', $data)->render();

        $current = $data['current'];
        $filename = public_path() . '/tmp/' . 'report_' . $userId . '_' . $current->format('YmdHis') . '.pdf';

        $pdf = App::make('dompdf');
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
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
        unlink($filename);
        return Redirect::back();
    }
}
