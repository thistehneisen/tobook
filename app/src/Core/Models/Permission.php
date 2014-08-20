<?php namespace App\Core\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public $visible = ['id', 'name'];
}
