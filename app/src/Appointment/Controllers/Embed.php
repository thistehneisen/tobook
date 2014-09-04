<?php namespace App\Appointment\Controllers;

use Hashids;
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
        return $this->render('index', [
            'link' => route('as.embed.embed', ['hash' => $this->user->hash])
        ]);
    }

    /**
     * Show the embed form to user
     *
     * @return View
     */
    public function preview()
    {
        return $this->render('preview', [
            'link' => route('as.embed.embed', ['hash' => $this->user->hash])
        ]);
    }

    /**
     * Display the booking form of provided user
     *
     * @param string $hash UserID hashed
     *
     * @return View
     */
    public function embed($hash)
    {
        // $decoded = Hashids::decrypt($hash);
        // $user = User::find($decoded[0]);

        return $this->render('embed', [
        ]);
    }
}
