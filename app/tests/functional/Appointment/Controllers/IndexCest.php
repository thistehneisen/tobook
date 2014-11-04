<?php namespace Test\Appointment\Controllers;

use App\Core\Models\User;
use Carbon\Carbon;
use \FunctionalTester;

/**
 * @group as
 */
class IndexCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs(User::find(70));
    }

    public function testGetIndex(FunctionalTester $I)
    {
        $I->amOnRoute('as.index');
        $I->canSeeResponseCodeIs(200);

        $today = Carbon::today();
        $I->seeInField('#calendar_date', $today->toDateString());

        $I->click(trans('as.index.tomorrow'), '.btn.btn-default');
        $I->seeInField('#calendar_date', with(clone $today)->addDay()->toDateString());

        $I->click(trans('as.index.today'), '.btn.btn-default');
        $I->seeInField('#calendar_date', $today->toDateString());

        $I->click('#prev-week');
        $I->seeInField('#calendar_date', with(clone $today)->subWeek()->toDateString());

        $I->click('#next-week');
        $I->seeInField('#calendar_date', $today->toDateString());

        $I->click('#prev-day');
        $I->seeInField('#calendar_date', with(clone $today)->subDay()->toDateString());

        $I->click('#next-day');
        $I->seeInField('#calendar_date', $today->toDateString());

        $weekDay = with(clone $today)->startOfWeek();
        foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $weekDayName) {
            $I->click('#day-' . $weekDayName);
            $I->seeInField('#calendar_date', $weekDay->toDateString());
            $weekDay->addDay();
        }
    }
}
