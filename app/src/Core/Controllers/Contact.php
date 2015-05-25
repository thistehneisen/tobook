<?php namespace App\Core\Controllers;

use Validator;
use Response;
use Input;
use Mail;
use Settings;

class Contact extends Base
{
    /**
     * Send contact message to admin
     *
     * @return Response
     */
    public function send()
    {
        $v = Validator::make(Input::all(), [
            'email'   => 'required|email',
            'message' => 'required'
        ]);

        if ($v->fails()) {
            return Response::json($v->errors(), 422);
        }

        $data = [
            'email'   => Input::get('email'),
            'content' => Input::get('message'),
        ];
        $to = Settings::get('contact_email', null);
        if ($to !== null) {
            Mail::queue('front.emails.contact', $data, function ($mail) use ($to) {
                $mail->to($to)
                    ->subject(trans('home.contact.subject'));
            });
        }

        return Response::json(['message' => trans('home.contact.sent')]);
    }
}
