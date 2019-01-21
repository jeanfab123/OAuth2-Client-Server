<?php

namespace Testing;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// -- A MODIFIER
require('settings.php');
// --

class ResourceServerTest extends TestCase
{

    const GOOD_AUTH = 'testclient:testpass';

    const BAD_TOKEN = '{"access_token":"areyousureisabadtoken?nonononono!!","expires_in":120,"token_type":"Bearer","scope":null}';

    const RESOURCES_SERVER_BASE_URI = RESOURCES_SERVER_BASE_URI;
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

    /**
     * @test
     */
    public function shouldFailWhenThereIsBadToken()
    {
        $this->sendClientToken(self::BAD_TOKEN, 401);
    }

    /**
     * @test
     */
    public function shouldValidWhenThereIsGoodToken()
    {

        $response = $this->sendClientCredentials(self::GOOD_AUTH);

        $tokenHeader = $response->getHeader('token');
        $token = isset($tokenHeader[0]) ? $tokenHeader[0] : null;

        $this->sendClientToken($token, 200);
    }

    /**
     * @privateClass
     */
    private function sendClientCredentials(string $auth, ?int $expectedResponseCode = null)
    {

        return $this->sendClientTokenOrCredentials('auth', $auth, $expectedResponseCode);
    }

    /**
     * @privateClass
     */
    private function sendClientToken(?string $token, ?int $expectedResponseCode = null)
    {

        return $this->sendClientTokenOrCredentials('token', $token, $expectedResponseCode);
    }

    /**
     * @privateClass
     */
    private function buildParamOptionsForSending(string $fieldTokenOrCredentials, ?string $tokenOrCredentials) : array
    {

        return [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                $fieldTokenOrCredentials => [$tokenOrCredentials],
            ],
        ];
    }

    /**
     * @privateClass
     */
    private function sendClientTokenOrCredentials(string $fieldTokenOrCredentials, ?string $tokenOrCredentials, ?int $expectedResponseCode)
    {

        $client = new Client(['base_uri' => self::RESOURCES_SERVER_BASE_URI]);

        try {

            $response = $client->get(self::RESOURCES_SERVER_END_POINT, $this->buildParamOptionsForSending($fieldTokenOrCredentials, $tokenOrCredentials));

            if ($expectedResponseCode) {
                $this->assertEquals($expectedResponseCode, $response->getStatusCode());
            } else {
                return $response;
            }

        } catch (RequestException $ex) {

            if ($expectedResponseCode) {
                $this->assertEquals($expectedResponseCode, $ex->getCode());
            } else {
                // -- Should not be used
                return $ex;
            }
        }
    }

}