<?php namespace App\Core\Models\Commission;
use Input, Response, Log, Settings, Config, Util, Redirect, App, Mail;
use App\Core\Models\CommissionLog;
use App\Core\Models\BusinessCommission;
use App\Core\Models\User;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use Carbon\Carbon;

class ToBookCounter extends Counter
{
    public function counterData($current, $perPage, $langPrefix, $date, $userId, $employeeId)
    {

        if (!empty($date)) {
            try {
                $current = Carbon::createFromFormat('Y-m-d', $date . '-01');
            } catch (\Exception $ex) {
                $current = Carbon::now();
            }
        }

        $startOfMonth    = $current->startOfMonth()->toDateTimeString();
        $endOfMonth      = $current->endOfMonth()->toDateTimeString();

        $user = User::findOrFail($userId);
        $status = (empty($employeeId))
            ? Employee::STATUS_EMPLOYEE
            : Employee::STATUS_FREELANCER;


        $bookings = Booking::getBookingsByEmployeeStatus(
            $user->id,
            $status,
            $employeeId,
            $perPage,
            $startOfMonth,
            $endOfMonth
        );

        $fields = [
            'created_at', 'booking_date', 'employee', 'name', 'price', 'commission_status', 'consumer_status','booking_status', 'notes'
        ];

        //in freelancer tab, hide employee column and change to customer name
        if(!empty($employeeId)){
            unset($fields[2]);
        }

        $months = Util::getMonthsSelection($current);

        $freelancers = $user->asEmployees()
            ->where('status', '=', Employee::STATUS_FREELANCER)
            ->get();

        $steadyCommision = Booking::countSteadyCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $paidDepositCommission = Booking::countPaidDepositCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $newConsumerCommission = Booking::countNewConsumerCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        //Total not received commission
        $totalCommission = Booking::totalCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        //Total received amount from paygate (paid + deposit)
        $totalReceiveFromPaygate = Booking::totalReceiveFromPaygate(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $commissionRate = Settings::get('commission_rate');
        $currencySymbol = Settings::get('currency');

        return [
            'items'                      => $bookings,
            'fields'                     => $fields,
            'months'                     => $months,
            'date'                       => $current->format('Y-m'),
            'langPrefix'                 => $langPrefix,
            'current'                    => $current,
            'user'                       => $user,
            'freelancers'                => $freelancers,
            'employeeId'                 => $employeeId,
            'commissionRate'             => $commissionRate,
            'currencySymbol'             => $currencySymbol,
            'steadyCommision'            => $steadyCommision,
            'paidDepositCommission'      => $paidDepositCommission,
            'newConsumerCommission'      => $newConsumerCommission,
            'totalCommission'            => $totalCommission,
            'totalReceiveFromPaygate'    => $totalReceiveFromPaygate,
        ];
    }

    public function reportData($current, $langPrefix, $date, $userId, $employeeId)
    {

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

        $status = null;

        if($employeeId == null || $employeeId > 0) {
            $status = (empty($employeeId))
                ? Employee::STATUS_EMPLOYEE
                : Employee::STATUS_FREELANCER;
        }

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

        $steadyCommision = Booking::countSteadyCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $paidDepositCommission = Booking::countPaidDepositCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $newConsumerCommission = Booking::countNewConsumerCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $totalCommission = Booking::totalCommission(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $totalReceiveFromPaygate = Booking::totalReceiveFromPaygate(
            $userId,
            $status,
            $employeeId,
            $startOfMonth,
            $endOfMonth
        );

        $fields = [
            'created_at','date', 'employee', 'customer', 'price', 'commission_status', 'consumer_status','booking_status', 'notes'
        ];

        //in freelancer tab, hide employee column and change to customer name
        if(!empty($employeeId)){
            unset($fields[2]);
        }

        $commissionRate = Settings::get('commission_rate');
        $currencySymbol = Settings::get('currency');

        return [
            'employeeBookings'           => $employeeBookings,
            'freelancersBookings'        => $freelancersBookings,
            'fields'                     => $fields,
            'date'                       => $current->format('Y-m'),
            'langPrefix'                 => $langPrefix,
            'current'                    => $current,
            'user'                       => $user,
            'employeeId'                 => $employeeId,
            'commissionRate'             => $commissionRate,
            'currencySymbol'             => $currencySymbol,
            'steadyCommision'            => $steadyCommision,
            'paidDepositCommission'      => $paidDepositCommission,
            'newConsumerCommission'      => $newConsumerCommission,
            'totalCommission'            => $totalCommission,
            'totalReceiveFromPaygate'    => $totalReceiveFromPaygate,
        ];
    }
}
