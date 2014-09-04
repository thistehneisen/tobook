<?php namespace App\Appointment\Controllers;

use Hashids, Input;
use App\Core\Models\User;
use App\Appointment\Models\ServiceCategory;

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
        $decoded = Hashids::decrypt($hash);
        $user = User::find($decoded[0]);

        $layoutId = (int) Input::get('l');
        if (!$layoutId) {
            $layoutId = 1;
        }

        $categories = ServiceCategory::OfUser($user->id)
            ->where('is_show_front', true)
            ->get();

        return $this->render('layout-'.$layoutId, [
            'categories' => $categories
        ]);
    }
}
