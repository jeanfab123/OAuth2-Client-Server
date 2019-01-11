<?php

namespace Testing;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ResourceServerTest extends TestCase
{

    const GOOD_LOGIN = 'testclient';
    const GOOD_PASSWORD = '$2y$10$O2AkWbnFnXbrrBkRSLbVn.IIgdQOySPwzt2eeucPYsGQSPCyPGkY2';

    const RESOURCES_SERVER_BASE_URI = 'http://localhost:9000';
    const SURVEY_GENERATION_END_POINT = '/survey-generation';

    /**
     * @test
     */
    public function shouldFailWhenThereIsNoCredentials()
    {
        $client = new Client(['base_uri' => self::RESOURCES_SERVER_BASE_URI]);
        try {
            $response = $client->get(self::SURVEY_GENERATION_END_POINT);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $ex) {
            $this->assertEquals(200, $ex->getCode());
        }
    }

    /**
     * @test
     */
    public function shouldFailWhenThereIsBadCredentials()
    {
        $client = new Client(['base_uri' => self::RESOURCES_SERVER_BASE_URI]);
 
        try {
            $response = $client->request(
                'GET',
                self::SURVEY_GENERATION_END_POINT,
                [
                    'headers' => [
                        'auth' =>
                            [
                                'myfakelogin', 
                                'myfakepassword'
                            ]
                        ]
                ]
            );

            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $ex) {
            $this->assertEquals(200, $ex->getCode());
        }
    }



/*
    public function shouldValidWhenThereIsGoodCredentials()
    {
        $client = new Client(['base_uri' => self::RESOURCES_SERVER_BASE_URI]);
 
        $response = $client->request(
            'GET',
            self::SURVEY_GENERATION_END_POINT,
            ['headers' =>
                ['auth' =>
                    [
                        self::GOOD_LOGIN, 
                        self::GOOD_PASSWORD
                    ]
                ]
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());
    }
*/

    /*
    public function shouldFailWhenThereIsBadToken() {

        $client = new Client(['base_uri' => $this->resourcesServerBaseUri]);
        $response = $client->request(
            'GET',
            self::SURVEY_GENERATION_END_POINT,
            ['headers' =>
                [
                    'Authorization' => "Bearer mybadtoken-wzarflodsbn$"
                ]
            ]
        )->getBody()->getContents();

    }
    */

}