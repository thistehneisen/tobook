<?php namespace App\Appointment\Controllers;

use Hashids, Input, View, Session, Redirect, URL, Config, Validator, Event, App;
use App\Core\Models\User;

class Embed extends AsBase
{
    protected $viewPath = 'modules.as.embed';

    /**
     * Show embeded link for user to install on their website
     *
     * @return View
     */
    public function index()
    {
        $links  = [];
        foreach (Config::get('varaa.languages') as $lang) {
            $links[$lang] = URL::route('as.embed.embed', ['hash' => $this->user->hash], true, null, $lang);
        }

        return $this->render('index', [
            'links' => $links
        ]);
    }

    /**
     * Show the embed form to user
     *
     * @return View
     */
    public function preview()
    {
        return Redirect::route('as.embed.embed', ['hash' =>  $this->user->hash, 'l' => 2]);
    }

}
