<?php namespace App\Cart\Controllers;

use Cart;
use Response, Lang;

class Index extends \AppController
{
    protected $viewPath = 'front.cart';

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
}
