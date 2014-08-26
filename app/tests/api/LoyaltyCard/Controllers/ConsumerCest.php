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

    public function testListConsumers(ApiTester $I)
    {
        $I->wantTo('Return all consumers');
        $I->sendGET('consumers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function testCreateConsumerWithInvalidData(ApiTester $I)
    {
        $I->wantTo('Create an failed consumer');
        $I->sendPOST('consumers', [
            'first_name'    => 'First',
            'last_name'     => 'Last',
            'phone'         => '987654313',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Invalid data']);
    }

    public function testCreateConsumer(ApiTester $I)
    {
        $I->wantTo('Create an consumers');
        $I->sendPOST('consumers', [
            'first_name'    => 'First',
            'last_name'     => 'Last',
            'email'         => '123@example.com',
            'phone'         => '123456789',
            'address'       => 'Osoite testi',
            'city'          => 'Kaupunki 1',
            'postcode'      => '12345',
            'country'       => 'Suomi',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Consumer created']);
        $consumer_id = $I->grabDataFromJsonResponse('created');
        $I->sendGET('consumers/'.$consumer_id);
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'email'         => '123@example.com',
        ]);
    }

    public function testShowConsumer(ApiTester $I)
    {
        $I->wantTo('See one consumer');
        $I->sendGET('consumers/1');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'first_name'    => 'Tung',
        ]);
    }

    public function testUpdateConsumer(ApiTester $I)
    {
        $I->wantTo('Update one consumer');
        $I->sendPUT('consumers/6', [
            'first_name'    => 'New first',
        ]);
        $I->seeResponseCodeIs('201');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Consumer updated']);
        $I->wantTo('See updated consumer');
        $I->sendGET('consumers/6');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'first_name'    => 'New first',
        ]);
    }

    public function testDeleteConsumer(ApiTester $I)
    {
        $I->wantTo('Delete one consumer');
        $I->sendDELETE('consumers/6');
        $I->seeResponseCodeIs('204');
        $I->seeResponseIsJson();
        //$I->seeResponseContainsJson(['message' => 'Consumer deleted']);
    }
}
