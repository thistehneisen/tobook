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

    /**
     * Create a new message bag containing success messages
     *
     * @param  mixed $content 
     * @param  string $title   (optional)
     *
     * @return MessageBag
     */
    protected function successMessageBag($content, $title = null)
    {
        if ($title === null) {
            $title = trans('common.success');
        }

        $content = (array) $content;
        return new MessageBag([
            'success' => [
                'title'   => $title,
                'content' => $content
            ]
        ]);
    }

    /**
     * Create a new message bag for errors
     *
     * @param  mixed $content 
     *
     * @return MessageBag
     */
    protected function errorMessageBag($content)
    {
        return new MessageBag((array) $content);
    }
}
