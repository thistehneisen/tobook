<?php namespace App\Core\Models\Commission;

class ToBookCounter extends Counter
{
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

        $steadyCommision = Booking::countSteadyCommission();
        $paidDepositCommission = Booking::countPaidDepositCommission();
        $newConsumerCommission = Booking::countNewConsumerCommission();

        $fields = [
            'created_at','date', 'employee', 'customer', 'price', 'commission_status', 'booking_status', 'notes'
        ];

        //in freelancer tab, hide employee column and change to customer name
        if(!empty($employeeId)){
            unset($fields[2]);
        }

        $commissionRate = Settings::get('commission_rate');
        $currencySymbol = Settings::get('currency');

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
}
