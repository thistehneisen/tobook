<?php
namespace LoyaltyCard\Controllers;
use \ApiTester;

class OfferCest
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
        $I->wantTo('Return all offers');
        $I->sendGET('offers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        // Store

        // Invalid data
        $I->wantTo('Create an failed offers');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('offers', [
            'name'      => 'Offer 1',
            'free'      => '1',
            'active'    => '1',
            'auto_add'  => '0',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Invalid data']);

        // Valid data
        /*$I->wantTo('Create an offers');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('offers', [
            'name'      => 'Offer 1',
            'required'  => '20',
            'free'      => '1',
            'active'    => '1',
            'auto_add'  => '0',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Offer created']);*/

        // Show
        $I->wantTo('See one offer');
        $I->sendGET('offers/1');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => 1,
            'name' => 'Offer 1',
        ]);

        // Update
        $I->wantTo('Update one offer');
        $I->sendPUT('offers/2', [
            'name' => 'Offer Two',
            'required' => '200',
            'free_service' => '5',
        ]);
        $I->seeResponseCodeIs('201');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Offer updated']);

        // Destroy
        /*$I->wantTo('Delete one offer');
        $I->sendDELETE('offers/6', null);
        $I->seeResponseCodeIs('204');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Offer deleted']);*/
    }
}
