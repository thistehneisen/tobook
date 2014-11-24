<?php namespace Test\Functional\Auth;

use App\Core\Models\User;
use Test\Traits\Models;
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
        $registered = $this->_register($I);
        $email = $registered['email'];

        $user = User::where('email', $email)->first();
        $I->assertNotNull($user, '$user');
        $I->assertEquals($email, $user->email, '$user->email');
        $I->assertEquals(0, $user->confirmed, '$user->confirmed');
        $I->assertTrue($user->is_consumer, '$user->is_consumer');

        $consumer = $user->consumer;
        $I->assertEquals($email, $consumer->email, '$consumer->email');
        $I->assertEquals($registered['firstName'], $consumer->first_name, '$consumer->first_name');
        $I->assertEquals($registered['lastName'], $consumer->last_name, '$consumer->last_name');
        $I->assertEquals($registered['phone'], $consumer->phone, '$consumer->phone');
    }

    public function testLogin(FunctionalTester $I)
    {
        $registered = $this->_register($I);
        $email = $registered['email'];
        $password = $registered['password'];

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
        $firstName = 'First ' . time();
        $lastName = 'Last ' . time();
        $phone = time();

        $I->fillField('email', $email);
        $I->fillField('#register-password', $password);
        $I->fillField('password_confirmation', $password);
        $I->fillField('first_name', $firstName);
        $I->fillField('last_name', $lastName);
        $I->fillField('phone', $phone);

        $I->click('#btn-register');
        $I->seeCurrentRouteIs('auth.register.done');

        return compact([
            'email',
            'password',
            'firstName',
            'lastName',
            'phone',
        ]);
    }
}
