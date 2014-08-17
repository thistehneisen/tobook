<?php namespace App\Controllers\Admin;

use App, Config, Request, Redirect, Input, Confide, Session, Auth, Setting;

class Settings extends Base
{
    public function index()
    {
        return $this->render('settings.index', [
            'location'          => Setting::get('location'),
            'freephone'         => Setting::get('free-phone'),
            'telephone'         => Setting::get('telephone'),
            'fax'               => Setting::get('fax'),
            'facebook_page'     => Setting::get('facebook-page'),
            'google_page'       => Setting::get('google-page'),
            'pinterest_page'    => Setting::get('pinterest-page'),
            'linkedin_page'     => Setting::get('linkedin-page'),
            'rss_page'          => Setting::get('rss-page'),
        ]);
    }

    public function doUpdate()
    {

        Setting::set('location', Input::get('location', ''));
        Setting::set('free-phone', Input::get('free-phone', ''));
        Setting::set('telephone', Input::get('telephone', ''));
        Setting::set('fax', Input::get('fax', ''));
        Setting::set('facebook-page', Input::get('facebook-page', ''));
        Setting::set('google-page', Input::get('google-page', ''));
        Setting::set('pinterest-page', Input::get('pinterest-page', ''));
        Setting::set('rss-page', Input::get('rss-page', ''));

        return Redirect::route('admin.settings.index');
    }
}
