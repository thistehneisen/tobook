<?php namespace App\Consumers;

class DuplicatedException extends \Exception
{
    protected $consumer;

    public function setDuplicated($consumer)
    {
        $this->consumer = $consumer;
    }

    public function getDuplicated($consumer)
    {
        return $this->consumer;
    }
}
