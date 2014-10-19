<?php namespace App\Cart\Controllers;

use Cart, Response, Lang, Confide, Redirect, Session, Payment, Event;

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
        // We will force use to register as consumer or login at this point
        if (!Confide::user()) {
            // Put the URL of cart checkout, so that when user logins/registers,
            // he/she will continue checking out process
            Session::put('url.intended', route('cart.checkout'));

            return Redirect::route('consumer.auth.register')
                ->with(
                    'messages',
                    $this->successMessageBag(
                        trans('home.cart.why_content'),
                        trans('home.cart.why_heading')
                    )
                )
                // Because we're thirsty for money, so the easier checkout
                // process, the more money will come
                ->with('fromCheckout', true);
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

        if ($cart === null || $cart->total <= 0) {
            return Redirect::route('cart.checkout')
                ->withErrors($this->errorMessageBag(trans('home.cart.err.zero_amount')), 'top');
        }

        // Fire the payment.process so that cart details could update themselves
        Event::fire('payment.process', [$cart]);

        // Attach the current consumer to the cart
        $cart->consumer()->associate(Confide::user()->consumer);
        $cart->save();

        $goToPaygate = true;
        return Payment::redirect($cart, $cart->total, $goToPaygate);
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
        Event::fire('cart.remove', $id);

        Cart::current()->remove($id);
    }
}
