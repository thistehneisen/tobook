<?php
namespace LoyaltyCard\Controllers;
use \ApiTester;

class ConsumerCest
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
        // $I->wantTo('Return all consumers');
        // $I->sendGET('consumers');
        // $I->seeResponseCodeIs(200);
        // $I->seeResponseIsJson();

        // Store

        // Invalid data
        // $I->wantTo('Create an failed consumer');
        // $I->sendPOST('consumers', [
        //     'first_name'    => 'First',
        //     'last_name'     => 'Last',
        //     'phone'         => '987654313',
        // ]);
        // $I->seeResponseCodeIs(400);
        // $I->seeResponseIsJson();
        // $I->seeResponseContainsJson(['message' => 'Invalid data']);

        // // Valid data
        // $I->wantTo('Create an consumers');
        // $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        // $I->sendPOST('consumers', [
        //     'first_name'    => 'First',
        //     'last_name'     => 'Last',
        //     'email'         => '123@example.com',
        //     'phone'         => '123456789',
        //     'address'       => 'Osoite testi',
        //     'city'          => 'Kaupunki 1',
        //     'postcode'      => '12345',
        //     'country'       => 'Suomi',
        // ]);
        // $I->seeResponseCodeIs(201);
        // $I->seeResponseIsJson();
        // $I->seeResponseContainsJson(['message' => 'Consumer created']);

        // // Show
        // $I->wantTo('See one consumer');
        // $I->sendGET('consumers/2');
        // $I->seeResponseCodeIs('200');
        // $I->seeResponseIsJson();
        // $I->seeResponseContainsJson([
        //     'id'            => 2,
        //     'first_name'    => 'Tung',
        //     'email'         => 'tung.nguyen@metropolia.fi',
        // ]);

        // Update
        $I->wantTo('Update one consumer');
        $I->sendPUT('consumers/5', [
            'first_name' => 'Five',
        ]);
        $I->seeResponseCodeIs('201');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Consumer updated']);
        $I->wantTo('See updated consumer');
        $I->sendGET('consumers/5');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id'            => 5,
            'first_name'    => 'Five',
        ]);
    }
}
