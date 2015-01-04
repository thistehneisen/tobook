<?php namespace App\Core\Controllers\Admin;

use Input, Response;
use App\Core\Models\CommissionLog;
use App\Core\Models\User;

class Commissions extends Base
{
    /**
     * Add a new commission to user
     *
     * @param int $userId
     *
     * @return mixed
     */
    public function add($userId)
    {
        $user = User::findOrFail($userId);

        try {
            $input = Input::all();
            $input['action'] = 'add';

            $item = new CommissionLog($input);
            $item->user()->associate($user);
            $item->saveOrFail();

            return Response::json(['message' => 'OK']);
        } catch (\Exception $ex) {
            return Response::json(['message' => $ex->getMessage()]);
        }
    }

    public function subtract($userId)
    {

    }

    public function index($userId)
    {

    }
}
