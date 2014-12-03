<?php namespace Test\Functional;

class Base
{
    protected $user;

    public function _before(\FunctionalTester $I)
    {
        $this->user = Fixture::user();

        $I->amLoggedAs($this->user);
    }
}
