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

    public function testListOffers(ApiTester $I)
    {
        // Index
        $I->wantTo('Return all offers');
        $I->sendGET('offers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    // public function testCreateOfferWithInvalidData(ApiTester $I)
    // {
    //     $I->wantTo('Create an failed offers');
    //     $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
    //     $I->sendPOST('offers', [
    //         'name'      => 'Offer 1',
    //         'free'      => '1',
    //         'active'    => '1',
    //         'auto_add'  => '0',
    //     ]);
    //     $I->seeResponseCodeIs(400);
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson(['message' => 'Invalid data']);
    // }

    // public function testCreateOffer(ApiTester $I)
    // {
    //     $I->wantTo('Create an offers');
    //     $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
    //     $I->sendPOST('offers', [
    //         'name'      => 'Offer 1',
    //         'required'  => '20',
    //         'free'      => '1',
    //         'active'    => '1',
    //         'auto_add'  => '0',
    //     ]);
    //     $I->seeResponseCodeIs(201);
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson(['message' => 'Offer created']);
    //     $offer_id = $I->grabDataFromJsonResponse('created');
    //     $I->sendGET('offers/'.$offer_id);
    //     $I->seeResponseCodeIs('200');
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson([
    //         'id'      => $offer_id,
    //     ]);
    // }

    public function testShowOffer(ApiTester $I)
    {
        $I->wantTo('See one offer');
        $I->sendGET('offers/1');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => 1,
        ]);
        $I->seeResponseContainsJson([
            'name' => 'Offer 1',
        ]);
    }

    // public function testUpdateOffer(ApiTester $I)
    // {
    //     // Update
    //     $I->wantTo('Update one offer');
    //     $I->sendPUT('offers/2', [
    //         'name' => 'Offer Two',
    //         'required' => '200',
    //         'free_service' => '5',
    //     ]);
    //     $I->seeResponseCodeIs('201');
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson(['message' => 'Offer updated']);
    //     $I->wantTo('See updated offer');
    //     $I->sendGET('offers/2');
    //     $I->seeResponseCodeIs('200');
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson([
    //         'name' => 'Offer Two',
    //     ]);
    // }

    public function testDeleteOffer(ApiTester $I)
    {
        // $I->wantTo('Delete one offer');
        // $I->sendDELETE('offers/11', null);
        // $I->seeResponseCodeIs('204');
        // $I->seeResponseIsJson();

        $I->wantTo('Check if offer is soft deleted');
        $I->sendGET('offers');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson(['id' => 11]);

        $I->sendGET('offers/11');
        $I->seeResponseCodeIs('404');
    }

    // public function testUseOfferWithoutConsumerID(ApiTester $I)
    // {
    //     $I->wantTo('Use Offer Without Consumer ID');
    //     $I->sendPOST('use/offers/1');
    //     $I->seeResponseCodeIs('400');
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson(['message' => 'Customer ID missing']);
    // }

    // public function testUseOffer(ApiTester $I)
    // {
    //     $I->wantTo('Use Offer');
    //     $I->sendPOST('use/offers/1', [
    //         'customer_id'   => '7',
    //     ]);
    //     $I->seeResponseCodeIs('200');
    //     $I->seeResponseIsJson();

    //     $I->wantTo('Use Offer again and see error');
    //     $I->sendPOST('use/offers/1', [
    //         'customer_id'   => '7',
    //     ]);
    //     $I->seeResponseCodeIs('400');
    //     $I->seeResponseIsJson();
    //     $I->seeResponseContainsJson(['message' => 'Not enough free service']);
    // }
}
