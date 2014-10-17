<?php namespace App\Cart\Controllers;

use Cart, Response, Lang, Confide, Redirect, Session;

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
                );
        }

        return 'Hello';
    }
}
