<?php namespace App\Appointment\Models;
use Carbon\Carbon;
use App\Appointment\Models\BookingExtraService;
use App\Cart\CartDetailInterface;

class BookingService extends \App\Appointment\Models\Base implements CartDetailInterface
{
    protected $table = 'as_booking_services';

    public $fillable = [
        'tmp_uuid',
        'date',
        'start_at',
        'end_at',
        'modify_time',
        'service_time_id',
        'is_reminder_email',
        'is_reminder_sms'
    ];

    private $extraServices;

    public function getCartStartAt()
    {
        $service = (!empty($this->serviceTime->id))
            ? $this->serviceTime
            : $this->service;
        $startTime = $this->getStartAt();
        $startTime->addMinutes($service->before);
        return $startTime;
    }

    public function getCartEndAt()
    {
        $service = (!empty($this->serviceTime))
            ? $this->serviceTime
            : $this->service;
        //Total include extra services and plus time
        $total = 0;
        $plustime = $this->employee->getPlustime($service->id);
        if(empty($this->extraServices)){
            $this->calculateExtraServices();
        }
        $extraServiceTime = $this->extraServices['length'];
        $total = $extraServiceTime + $service->during + $plustime;
        $startTime = $this->getCartStartAt();
        $endTime = with(clone $startTime)->addMinutes($total);
        return $endTime;
    }

    public function calculateExtraServices()
    {
        if(empty($this->extraServices))
        {
            $extraServices     = BookingExtraService::where('tmp_uuid', $this->tmp_uuid)->get();
            $extraServiceTime  = 0;
            $extraServicePrice = 0;
            foreach ($extraServices as $extraService) {
                $extraServiceTime  += $extraService->length;
                $extraServicePrice += $extraService->length;
            }
            $this->extraServices['length'] = $extraServiceTime;
            $this->extraServices['price']  = $extraServicePrice;
        }
    }

    public function getExtraServicePrice()
    {
        if(empty($this->extraServices)){
            $this->calculateExtraServices();
        }
        return (int) $this->extraServices['price'];
    }

    public function getExtraServiceTime()
    {
        if(empty($this->extraServices)){
            $this->calculateExtraServices();
        }
        return (int) $this->extraServices['length'];
    }

    public function getEmployeePlustime()
    {
        return $this->employee->getPlustime($this->service->id);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    //TODO convert string to Carbon object
    // public function getStartAtAttribute()
    // {

    // }

    // public function getEndAtAttribute()
    // {

    // }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

    public function service()
    {
       return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function serviceTime()
    {
       return $this->belongsTo('App\Appointment\Models\ServiceTime');
    }

    public function employee()
    {
       return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    //--------------------------------------------------------------------------
    // CART DETAIL
    //--------------------------------------------------------------------------
    /**
     * @{@inheritdoc}
     */
    public function getCartDetailName()
    {
        // TODO
        return '';
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailOriginal()
    {
        $price = $this->getCartDetailPrice();

        return (object) [
            'datetime'      => $this->date,
            'price'         => $price,
            'service_name'  => $this->service->name,
            'employee_name' => $this->employee->name,
            'start_at'      => $this->getCartStartAt()->format('H:i'),
            'end_at'        => $this->getCartEndAt()->format('H:i'),
            'uuid'          => $this->tmp_uuid,
            'instance'      => $this
        ];
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailQuantity()
    {
        return 1;
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailPrice()
    {
        $this->calculateExtraServices();
        $price = (!empty($this->serviceTime->id))
            ? $this->serviceTime->price
            : $this->service->price;

        $price += $this->getExtraServicePrice();

        return $price;
    }

}
