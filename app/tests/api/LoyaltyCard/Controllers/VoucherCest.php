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
    public function testListVouchers(ApiTester $I)
    {
        $I->wantTo('Return all vouchers');
        $I->sendGET('vouchers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function testCreateVoucherWithInvalidData(ApiTester $I)
    {
        $I->wantTo('Create an failed vouchers');
        $I->sendPOST('vouchers', [
            'name'      => 'Voucher 1',
            'value'     => '1',
            'active'    => '1',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Invalid data']);
    }

    // public function testCreateVoucher(ApiTester $I)
    // {
    //     $I->wantTo('Create an vouchers');
    //     $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
    //     $I->sendPOST('vouchers', [
    //         'name'      => 'Voucher 1',
    //         'required'  => '20',
    //         'value'     => '1',
    //         'type'      => 'Percent',
    //         'active'    => '1',
    //     ]);
    //     $I->seeResponseCodeIs(201);
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson(['message' => 'Voucher created']);
    //     $voucher_id = $I->grabDataFromJsonResponse('created');
    //     $I->sendGET('vouchers/'.$voucher_id);
    //     $I->seeResponseCodeIs('200');
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson([
    //         'id'      => $voucher_id,
    //     ]);
    // }

    public function testShowVoucher(ApiTester $I)
    {
        $I->wantTo('See one voucher');
        $I->sendGET('vouchers/1');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => 1,
            'name' => 'Voucher 1',
        ]);
    }

    public function testUpdateVoucher(ApiTester $I)
    {
        $I->wantTo('Update one voucher');
        $I->sendPUT('vouchers/3', [
            'name' => 'Voucher Three',
            'required' => '200',
            'value' => '10',
        ]);
        $I->seeResponseCodeIs('201');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Voucher updated']);
        $I->sendGET('vouchers/3');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name'  => 'Voucher Three',
        ]);
    }

    public function testDeleteVoucher(ApiTester $I)
    {
        $I->wantTo('Delete one voucher');
        $I->sendDELETE('vouchers/3', null);
        $I->seeResponseCodeIs('204');
        $I->seeResponseIsJson();
    }
}
