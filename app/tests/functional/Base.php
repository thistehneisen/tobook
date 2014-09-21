<?php namespace Test\Functional;

class Base
{
    protected function login(\FunctionalTester $I)
    {
        $I->amLoggedAs(Fixture::user());
    }
}
