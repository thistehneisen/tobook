<?php namespace Test\Appointment\Controllers;

use \FunctionalTester;

/**
 * @group as
 */
class AuthCest
{
    public function testNoLoginGetRedirected(FunctionalTester $I)
    {
        $e = null;
        try {
            $I->amOnRoute('as.index');
        } catch (\Exception $_e) {
            $e = $_e;
        }

        $I->assertNotNull($e, 'exception');
    }
}
