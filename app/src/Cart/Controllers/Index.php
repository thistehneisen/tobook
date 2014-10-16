<?php namespace App\Cart\Controllers;

use Response;

class Index extends \AppController
{
    public function index()
    {
        $json['html'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus odit molestiae sunt ab maiores unde suscipit inventore fugit, obcaecati voluptatibus. Ullam a numquam autem dignissimos, aspernatur iure iusto eveniet non.';
        return Response::json($json);
    }
}
