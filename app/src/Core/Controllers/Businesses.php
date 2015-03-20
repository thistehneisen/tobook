<?php namespace App\Core\Controllers;

use Validator, Redirect, Input;

class Businesses extends Front
{
    public function contact($id, $slug)
    {
        $v = Validator::make(Input::all(), [
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
            'captcha' => 'required|captcha',
        ]);

        if ($v->fails()) {
            return Redirect::route('business.index', ['id' => $id, 'slug' => $slug])
                ->withErrors($v);
        }
    }
}
