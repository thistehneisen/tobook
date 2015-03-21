<?php namespace App\Core\Controllers;

use Validator, Redirect, Input, Mail, Settings;
use App\Core\Models\User;

class Businesses extends Front
{
    /**
     * Send a message directly to the shop owner
     *
     * @param int    $id   User ID
     * @param string $slug
     *
     * @return Redirect
     */
    public function contact($id, $slug)
    {
        $url = route('business.index', [
            'id'   => $id,
            'slug' => $slug,
            'src'  => 'contact'
        ]);

        $v = Validator::make(Input::all(), [
            'contact_name'    => 'required',
            'contact_email'   => 'required|email',
            'contact_message' => 'required',
            // 'captcha' => 'required',
        ]);

        // Send email to business
        $subject = trans('home.business.contact.subject');

        return $this->sendEmail($id, $v, $url, $subject, trans('home.business.contact.mail', [
            'name'    => e(Input::get('contact_name')),
            'email'   => e(Input::get('contact_email')),
            'phone'   => e(Input::get('contact_phone')),
            'message' => e(Input::get('contact_message')),
            'footer'  => Settings::get('footer_contact_message')
        ]));
    }

    /**
     * Auxilary method to send email to shop owners
     *
     * @param int      $businessId
     * @param Validaor $validator
     * @param string   $url          Return URL
     * @param string   $emailContent
     *
     * @return Redirect
     */
    private function sendEmail($businessId, $validator, $url, $emailSubject, $emailContent)
    {
        if ($validator->fails()) {
            return Redirect::to($url)->withInput()->withErrors($validator);
        }

        $business = User::findOrFail($businessId);
        Mail::send('front.contact.email',
            ['content' => $emailContent],
            function ($msg) use ($business, $emailSubject) {
                return $msg->to($business->email)->subject($emailSubject);
            });

        return Redirect::to($url)->with('messages', $this->successMessageBag(
            trans('home.business.contact.sent')
        ));
    }

    /**
     * Send a message to remind the shop owner to use our booking system
     *
     * @param int    $id
     * @param string $slug
     *
     * @return Redirect
     */
    public function request($id, $slug)
    {
        $url = route('business.index', [
            'id'   => $id,
            'slug' => $slug,
            'src'  => 'request'
        ]);

        $v = Validator::make(Input::all(), [
            'request_name'  => 'required',
            'request_email' => 'required|email',
        ]);

        $subject = trans('home.business.request.subject');

        return $this->sendEmail($id, $v, $url, $subject, trans('home.business.request.mail', [
            'name'  => e(Input::get('request_name')),
            'email' => e(Input::get('request_email')),
        ]));
    }
}
