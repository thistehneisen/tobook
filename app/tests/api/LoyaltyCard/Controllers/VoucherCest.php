<?php
namespace LoyaltyCard\Controllers;
use \ApiTester;

class VoucherCest
{
    public function _before(ApiTester $I)
    {
        $I->amHttpAuthenticated('capis', '123456');
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        // Index
        $I->wantTo('Return all vouchers');
        $I->sendGET('vouchers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        // Store

        // Invalid data
        $I->wantTo('Create an failed vouchers');
        $I->sendPOST('vouchers', [
            'name'      => 'Voucher 1',
            'value'     => '1',
            'active'    => '1',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Invalid data']);

        // Valid data
        /*$I->wantTo('Create an vouchers');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('vouchers', [
            'name'      => 'Voucher 1',
            'required'  => '20',
            'value'     => '1',
            'type'      => 'Percent',
            'active'    => '1',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Voucher created']);*/

        // Show
        $I->wantTo('See one voucher');
        $I->sendGET('vouchers/1');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => 1,
            'name' => 'Voucher 1',
        ]);

        // Update
        $I->wantTo('Update one voucher');
        $I->sendPUT('vouchers/3', [
            'name' => 'Voucher Two',
            'required' => '200',
            'value' => '10',
        ]);
        $I->seeResponseCodeIs('201');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Voucher updated']);
    }
}
