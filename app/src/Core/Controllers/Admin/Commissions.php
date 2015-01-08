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
            'userId' => $userId
        ]);
    }

    /**
     * Add a new commission to user
     *
     * @param int $userId
     *
     * @return Response
     */
    public function add($userId)
    {
        return $this->performAction('add', $userId);
    }

    /**
     * Subtract/refund to a user
     *
     * @param int $userId
     *
     * @return Response
     */
    public function subtract($userId)
    {
        return $this->performAction('subtract', $userId);
    }

    /**
     * Auxilary function to perform action on user's commissions
     *
     * @param string $action
     * @param int    $userId
     *
     * @return Response
     */
    protected function performAction($action, $userId)
    {
        $user = User::findOrFail($userId);

        try {
            $input = Input::all();
            $input['action'] = $action;

            $item = new CommissionLog($input);
            $item->user()->associate($user);
            $item->saveOrFail();

            return Response::json(['message' => trans('admin.commission_done')]);
        } catch (\Exception $ex) {
            Log::warning($ex->getMessage(), [
                'context' => 'admin.users.commissions',
                'user' => $userId
            ]);

            return Response::json(['message' => trans('admin.commission_fail')], 500);
        }
    }

    public function index($userId)
    {

    }
}
