<?php namespace App\Cart\Controllers;

use App\Core\Models\User;
use Cart;
use Consumer;
use Input;
use Log;
use Redirect;
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
        // $user = User::find(70);

        // Must-have fields
        $fields = [
            'first_name' => ['required' => true],
            'last_name'  => ['required' => true],
            'phone'      => ['required' => true],
            'email'      => ['required' => true],
        ];

        // There is possibility that user puts services of different businesses
        // inside the cart, so we need to merge their booking form settings and
        // ask for additional data
        // @TODO
        // $keys = [
        //     'email',
        //     'address',
        //     'city',
        //     'postcode',
        //     'country',
        //     'notes',
        // ];

        // foreach ($keys as $key) {
        //     $value = (int) $user->asOptions->get($key, 1);
        //     if ($value && $value > 1) {
        //         $fields[$key] = ['required' => $value === 3];
        //     }
        // }
        return $this->render('consumer', [
            'fields'    => $fields,
            // 'showTerms' => $user->asOptions->get('terms_enabled', 1) > 1
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
