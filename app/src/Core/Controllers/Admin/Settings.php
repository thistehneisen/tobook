<?php namespace App\Core\Controllers\Admin;

use App\Lomake\FieldFactory;
use Config;

class Settings extends Base
{
    protected $viewPath = 'admin.settings';

    /**
     * Show system settings
     *
     * @return View
     */
    public function index()
    {
        $definitions = Config::get('varaa.settings');
        $controls = [];

        foreach ($definitions as $name => $def) {
            $def['name'] = $name;
            $controls[] = FieldFactory::create($def);
        }

        return $this->render('index', [
            'controls' => $controls
        ]);
    }
}
