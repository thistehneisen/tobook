<?php namespace App\Appointment\Reports;

abstract class Base
{
    protected $cache;

    /**
     * Return raw data
     *
     * @return array
     */
    public function get()
    {
        return ($this->cache !== null) ? $this->cache : $this->fetch();
    }

    /**
     * Fetch data from database and return an array
     *
     * @return array
     */
    abstract protected function fetch();
}
