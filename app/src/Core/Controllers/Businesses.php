<?php namespace App\Core\Controllers;

use Validator, Redirect, Input, Mail, Settings;
use App\Core\Models\User;

class Businesses extends Front
{
    public function contact($id, $slug)
    {
        $v = Validator::make(Input::all(), [
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
            // 'captcha' => 'required',
        ]);

        if ($v->fails()) {
            return Redirect::route('business.index', ['id' => $id, 'slug' => $slug])
                ->withInput()
                ->withErrors($v);
        }

        // Send email to business
        $business = User::findOrFail($id);
        $content = trans('home.business.contact.mail', [
            'name'    => e(Input::get('name')),
            'email'   => e(Input::get('email')),
            'phone'   => e(Input::get('phone')),
            'message' => e(Input::get('message')),
            'footer'  => Settings::get('footer_contact_message')
        ]);

        Mail::send('front.contact.email', ['content' => $content], function ($msg) use ($business) {
            return $msg->to($business->email)
                ->subject(trans('home.business.contact.subject'));
        });

        return Redirect::route('business.index', ['id' => $id, 'slug' => $slug])
            ->with('messages', $this->successMessageBag(
                trans('home.business.contact.sent')
            ));
    }
}
