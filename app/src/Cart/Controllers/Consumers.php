<?php namespace App\Cart\Controllers;

use App;
use App\Core\Models\User;
use Cart;
use Consumer;
use Input;
use Log;
use Redirect;
use Settings;
use Validator;

class Consumers extends \AppController
{
    protected $viewPath = 'front.cart';

    /**
     * Display the form to enter consumer information
     *
     * @return View
     */
    public function index()
    {
        $fields = [
            'first_name' => ['required' => true],
            'last_name'  => ['required' => true],
            'phone'      => ['required' => true],
            'email'      => ['required' => true],
        ];

        // Get booking terms in the current language
        $terms = Settings::getBookingTerms(App::getLocale());

        return $this->render('consumer', [
            'terms' => $terms,
            'fields' => $fields,
            'showTerms' => false,
        ]);
    }

    /**
     * Collect consumer data
     *
     * @return Redirect
     */
    public function submit()
    {
        $v = Validator::make(Input::all(), [
            'first_name' => 'required',
            'last_name'  => 'required',
            'phone'      => 'required',
            'email'      => 'required|email',
        ]);

        if ($v->fails()) {
            return Redirect::route('cart.consumer')->withErrors($v);
        }

        try {
            $cart = Cart::current();
            // Make consumer from input
            $consumer = Consumer::make(Input::all(), $cart->user);
            // Attach consumer to the current cart
            $cart->consumer()->associate($consumer);
            $cart->save();

            // And redirect to checkout
            return Redirect::route('cart.checkout');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['cartId' => Cart::current()->id]);
        }
    }
}
