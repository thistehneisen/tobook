```
<?php
$booking = \App\Appoinment\Models\BookingService::find(123);
$deal    = \App\FlashDeal\Models\FlashDeal::find(456);
$voucher = \App\FlashDeal\Models\Voucher::find(789);

$cart = Cart::make();
$cart->addDetail($booking);
$cart->addDetails([$deal, $voucher]);


var_dump($cart->isEmpty());
var_dump($cart->total);
var_dump($cart->total_items);

foreach ($cart->details as $item) {
    var_dump(
        $item->name,
        $item->price,
        $item->quantity,
        // @TODO:
        // For example, a T-shirt might have size, color, etc.
        // a booking might have additional delivery note
        $item->options,
        // A class must implement CartDetailInterface in order to be added to
        // Cart. `model` refers to the original object.
        // @see: App\Appoinment\Model\BookingService
        $item->model
    );
}
```
