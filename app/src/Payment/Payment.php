<?php namespace App\Payment;

use Redirect, Session, Input, Validator;

class Payment
{
    /**
     * Receice an amount and redirect to payment page. We want to pass the cart
     * subtotal separately because the amount might be modified via coupon,
     * discount, etc.
     *
     * @param App\Core\Models\Cart $cart
     * @param double               $amount
     *
     * @return Redirect
     */
    public static function redirect($cart, $amount)
    {
        // Create a new transaction for this cart
        $transaction = new Models\Transaction(['amount' => $amount]);
        $transaction->cart()->associate($cart);
        $transaction->save();

        return Redirect::route('payment.index')
            ->with('transaction', $transaction);
    }

    /**
     * Get the current transaction of current request
     *
     * @return App\Payment\Models\Transaction
     */
    public static function current()
    {
        // The first data is flash session
        if (Session::has('transaction')) {
            $transaction = Session::get('transaction');

            // So we need to store it eternally
            Session::set('persisted_transaction', $transaction);
            return $transaction;
        }

        return Session::get('persisted_transaction');
    }

    /**
     * Prepare and redirect to paygate website
     *
     * @return Redirect
     */
    public static function purchase()
    {
        $v = Validator::make(Input::all(), [
            'number' => 'required|numeric',
            'exp'    => 'required',
            'cvv'    => 'required|numeric',
        ]);
        if ($v->fails()) {
            return Redirect::route('payment.index')
                ->withInput()
                ->withErrors($v);
        }

        $gateway = GatewayFactory::make(Input::get('gateway', 'Skrill'));

        $transaction = static::current();
        $token = explode('/', Input::get('exp'));
        $response = $gateway->purchase([
            'amount'   => $transaction->amount,
            'currency' => $transaction->currency,
            'card'     => [
                'number'      => Input::get('number'),
                'cvv'         => Input::get('cvv'),
                'expiryMonth' => $token[0],
                'expiryYear'  => $token[1],
            ]
        ]);
        if ($response->isSuccessful()) {
            return $gateway->success($response);
        } elseif ($response->isRedirect()) {
            return $response->redirect();
        }

        throw \Exception($response->getMessage());
    }

    /**
     * Handle notify request from gateway
     *
     * @param string $gateway
     *
     * @return void
     */
    public static function notify($gateway)
    {
        $gateway = GatewayFactory::make($gateway);
        return $gateway->notify();
    }

}
