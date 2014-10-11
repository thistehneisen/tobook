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
        // $v = Validator::make(Input::all(), [
        //     'number' => 'required|numeric',
        //     'exp'    => 'required',
        //     'cvv'    => 'required|numeric',
        // ]);
        // if ($v->fails()) {
        //     return Redirect::route('payment.index')
        //         ->withInput()
        //         ->withErrors($v);
        // }

        $gateway = GatewayFactory::make(Input::get('gateway', 'Skrill'));

        $card        = static::extractCardData(Input::all());
        $transaction = static::current();
        $response = $gateway->purchase($transaction, ['card' => $card]);

        // Update transaction with data from resposne
        $transaction->fill([
            'message'   => $response->getMessage(),
            'code'      => $response->getCode(),
            'reference' => $response->getTransactionReference(),
        ]);
        $transaction->save();

        if ($response->isSuccessful()) {
            // If the transction is succesful, we will call handler to
            // update its data
            return $gateway->success($response);
        } elseif ($response->isRedirect()) {
            // Or if it redirects to elsewhere, just follow it
            return $response->redirect();
        }

        // Otherwise, we have an error
        throw \Exception($response->getMessage());
    }

    /**
     * Extract card data from user input
     *
     * @return array
     */
    protected static function extractCardData($input)
    {
        // To make sure that there's always a value
        foreach (['exp', 'number', 'cvv'] as $key) {
            $input[$key] = array_key_exists($key, $input)
                ? $input[$key]
                : '';
        }

        $token = explode('/', $input['exp']);

        return [
            'number'      => $input['number'],
            'cvv'         => $input['cvv'],
            'expiryMonth' => isset($token[0]) ? $token[0] : '',
            'expiryYear'  => isset($token[1]) ? $token[1] : '',
        ];
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
