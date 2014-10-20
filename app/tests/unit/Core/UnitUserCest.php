<?php namespace Core\Models;

use App\Core\Models\User;
use App\Core\Models\Business;
use Appointment\Traits\Models;
use \UnitTester;

/**
 * @group core
 */
class UnitUserCest
{
    use Models;

    public function _before()
    {
        $this->_modelsReset();
    }

    public function testNewRecord(UnitTester $I)
    {
        $email = 'email' . time() . '@varaa.com';

        // create user record
        $user = new User([
            'email' => $email,
        ]);
        $user->password = 123456;
        $user->password_confirmation = 123456;
        $user->save();
        $I->assertNotNull($user->id, '$user->id');

        // unable to create user record (missing email field)
        $userMissing = new User();
        $userMissing->save();
        $I->assertNull($userMissing->id, '$userMissing->id');
    }
}
