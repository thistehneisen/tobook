<?php

class BaseCest
{
    protected function login(FunctionalTester $I)
    {
        $I->amLoggedAs(Fixture::user());
    }
}
