<?php namespace App\Core\Models;
use App\Appointment\Models\BookingService;

class CartDetail extends Base
{
     public $fillable = ['item', 'variant', 'quantity', 'price'];


    public static function make($data, $cart)
    {
        try {
            $detail = new self();
            $detail->fill($data);
            $detail->cart()->associate($cart);
            $detail->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            throw $ex;
        }

        return $detail;
    }

    public function getASBookingInfo()
    {
        $bookingService = BookingService::find($this->item);
        $bookingService->calculateExtraServices();

        $price = (!empty($bookingService->serviceTime->id))
            ? $bookingService->serviceTime->price
            : $bookingService->service->price;

        $price += $bookingService->getExtraServicePrice();

        $data = [
            'datetime'      => $bookingService->date,
            'price'         => $price,
            'service_name'  => $bookingService->service->name,
            'employee_name' => $bookingService->employee->name,
            'start_at'      => $bookingService->getCartStartAt()->format('H:i'),
            'end_at'        => $bookingService->getCartEndAt()->format('H:i'),
            'uuid'          => $bookingService->tmp_uuid
        ];
        return $data;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function cart()
    {
        return $this->belongsTo('App\Core\Models\Cart');
    }
}
