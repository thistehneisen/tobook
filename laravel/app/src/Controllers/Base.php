<?php namespace App\Controllers;

use Illuminate\Support\MessageBag;

class Base extends \Controller
{
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    protected function createMessageBag(array $messages = array())
    {
        return new MessageBag($messages);
    }
}
