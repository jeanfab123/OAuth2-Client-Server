<?php

namespace Testing;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ResourceServerTest extends TestCase
{

    const GOOD_AUTH = 'testclient:testpass';

    const RESOURCES_SERVER_BASE_URI = 'http://localhost:9000';
    const RESOURCES_SERVER_END_POINT = '/';

    /**
     * @test
     */
    public function shouldFailWhenThereIsNoCredentials()
    {
        $client = new Client(['base_uri' => self::RESOURCES_SERVER_BASE_URI]);

        try {

            $response = $client->get(self::RESOURCES_SERVER_END_POINT);

            $this->assertEquals(401, $response->getStatusCode());

        } catch (RequestException $ex) {

            $this->assertEquals(401, $ex->getCode());
        }
    }


    /**
     * @test
     */
    public function shouldFailWhenThereIsBadCredentials()
    {
        $this->sendClientCredentials('mybadlogin:mybadpassword', 401);
    }


    /**
     * @test
     */
    public function shouldValidWhenThereIsGoodCredentials()
    {
        $this->sendClientCredentials(self::GOOD_AUTH, 200);

    }


    /*
    public function shouldFailWhenThereIsBadToken() {

        $client = new Client(['base_uri' => self::RESOURCES_SERVER_BASE_URI]);
        $response = $client->request(
            'GET',
            self::RESOURCES_SERVER_END_POINT,
            ['headers' =>
                [
                    'Authorization' => "Bearer mybadtoken-wzarflodsbn$"
                ]
            ]
        )->getBody()->getContents();

    }
    */


    /**
     * @privateClass
     */
    private function sendClientCredentials(string $auth, int $expectedResponseCode) {

        $client = new Client(['base_uri' => self::RESOURCES_SERVER_BASE_URI]);

        try {

            $options = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'auth' => [$auth],
                ],
            ];

            $response = $client->get(self::RESOURCES_SERVER_END_POINT, $options);

            $this->assertEquals($expectedResponseCode, $response->getStatusCode());

        } catch (RequestException $ex) {

            $this->assertEquals($expectedResponseCode, $ex->getCode());
        }
    }
}