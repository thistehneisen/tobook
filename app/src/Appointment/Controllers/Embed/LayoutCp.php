<?php namespace App\Appointment\Controllers\Embed;

use Response;

class LayoutCp extends Base
{
    use Layout;

    public function getServices($hash)
    {
        $data = $this->handleIndex($hash);
        return Response::json([
            'categories' => $data['categories'],
            'business' => $data['user']->business,
        ]);
    }
}
