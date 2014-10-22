<?php namespace Test\Functional\Auth;

use App\Core\Models\User;
use Appointment\Traits\Models;
use FunctionalTester;

/**
 * @group core
 */
class ConsumerAuthCest
{
    use Models;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
    }

    public function testRegister(FunctionalTester $I)
    {
        list($email,) = $this->_register($I);

        $user = User::where('email', $email)->first();
        $I->assertNotNull($user, '$user');
        $I->assertEquals($email, $user->email, '$user->email');
        $I->assertEquals(0, $user->confirmed, '$user->confirmed');
        $I->assertTrue($user->is_consumer, '$user->is_consumer');
    }

    public function testLogin(FunctionalTester $I)
    {
        list($email, $password) = $this->_register($I);

        $I->dontSeeAuthentication();

        $user = User::where('email', $email)->first();
        \Confide::confirm($user->confirmation_code);

        $I->amOnRoute('auth.login');
        $I->canSeeInField('username', '');
        $I->canSeeInField('password', '');

        $I->fillField('username', $email);
        $I->fillField('password', $password);
        $I->click('#btn-login');

        $I->seeAuthentication();
    }

    private function _register($I)
    {
        $I->amOnRoute('consumer.auth.register');
        $I->canSeeInField('email', '');
        $I->canSeeInField('password', '');
        $I->canSeeInField('password_confirmation', '');

        $email = 'consumer' . time() . '@varaa.com';
        $password = 'Nordic characters (ä, ö, å)';
        $I->fillField('email', $email);
        $I->fillField('password', $password);
        $I->fillField('password_confirmation', $password);
        $I->click('#btn-register');
        $I->seeCurrentRouteIs('auth.register.done');

        return [$email, $password];
    }
}
