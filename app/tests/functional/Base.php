<?php namespace Test\Functional;

class Base
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedAs(Fixture::user());
    }
}
