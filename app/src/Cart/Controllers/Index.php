<?php namespace App\Cart\Controllers;

use App\Core\Models\User;
use Cart;
use Confide;
use Event;
use Input;
use Lang;
use Payment;
use Redirect;
use Response;

class Index extends \AppController
{
    protected $viewPath = 'front.cart';

    /**
     * Show the cart content via AJAX
     *
     * @return Response JSON
     */
    public function index()
    {
        $cart = Cart::current();

        $json['totalItems'] = $cart !== null
            ? $cart->total_items.' '.Lang::choice('home.cart.items', $cart->total_items)
            : trans('home.cart.empty');

        $json['content'] = $this->render('index', [
            'cart' => $cart
        ])->render();

        return Response::json($json);
    }

    /**
     * Show the cart content and prepare to checkout
     *
     * @return Redirect|View
     */
    public function checkout()
    {
        if (Cart::current()->consumer === null) {
            // Redirect to form to enter consumer information
            return Redirect::route('cart.consumer');
        }

        return $this->render('checkout', [
            'cart' => Cart::current()
        ]);
    }

    /**
     * Process to payment
     *
     * @return Redirect
     */
    public function payment()
    {
        $cart = Cart::current();

        $depositPayment = (Input::get('submit') === 'deposit_payment');
        $total = ($depositPayment) ? $cart->depositTotal : $cart->total;
        $cart->is_deposit_payment = $depositPayment;

        if ($cart === null || $total <= 0) {
            return Redirect::route('cart.checkout')
                ->withErrors($this->errorMessageBag(trans('home.cart.err.zero_amount')), 'top');
        }

        $user = Confide::user();
        if ($user && $user->is_business) {
            return Redirect::route('cart.checkout')
                ->withErrors($this->errorMessageBag(trans('home.cart.err.business')), 'top');
        }

        // Fire the payment.process so that cart details could update themselves
        Event::fire('payment.process', [$cart]);

        $goToPaygate = true;

        return Payment::redirect($cart, $total, $goToPaygate);
    }

    /**
     * Remove a cart detail from cart
     *
     * @param int $id
     *
     * @return void
     */
    public function remove($id)
    {
        // Fire an event when an item was remove from cart
        // We must fire event first, or the data will be removed and listeners
        // won't be able to fetch.
        Event::fire('cart.removed', $id);

        // TODO: handle error here if Cart::current() contains nothing
        Cart::current()->remove($id);
    }
}
