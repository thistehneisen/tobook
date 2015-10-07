<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Role;
use App\Core\Models\User;
use App\Haku\Searchers\Users as UsersSearcher;
use Auth;
use Confide;
use Config;
use Input;
use Lomake;
use Mail;
use Redirect;
use Session;
use View;

class Reviews extends Base
{
    use \CRUD;
    protected $viewPath = 'admin.reviews';

    protected $crudOptions = [
        'modelClass'  => 'App\Core\Models\Review',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'as.review',
        'actionsView' => 'admin.reviews.actions',
    ];
}