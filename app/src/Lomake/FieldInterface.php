<?php namespace App\Lomake;

interface FieldInterface
{
    /**
     * How the HTML of this field is structured
     *
     * @return View|string
     */
    public function render();
}
