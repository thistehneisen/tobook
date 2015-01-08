<?php namespace App\Core\Controllers\Admin;

use Input, Response, Log;
use App\Core\Models\CommissionLog;
use App\Core\Models\User;

class Commissions extends Base
{
    protected $viewPath = 'admin.commissions';

    /**
     * Show the modal to enter user commission
     *
     * @param int    $userId
     * @param string $action
     *
     * @return View
     */
    public function show($userId, $action)
    {
        return $this->render('modal', [
            'userId' => $userId,
            'action' => $action
        ]);
    }

    /**
     * Add/substract a new commission to user
     *
     * @param int    $userId
     * @param string $action
     *
     * @return Response
     */
    public function doAction($userId, $action)
    {
        $user = User::findOrFail($userId);

        try {
            $input = Input::all();
            $input['action'] = $action;

            $item = new CommissionLog($input);
            $item->user()->associate($user);
            $item->saveOrFail();

            return Response::json(['message' => trans('admin.commissions.done')]);
        } catch (\Exception $ex) {
            Log::warning($ex->getMessage(), [
                'context' => 'admin.users.commissions',
                'user' => $userId
            ]);

            return Response::json(['message' => trans('admin.commissions.fail')], 500);
        }
    }

    public function index($userId)
    {

    }
}
