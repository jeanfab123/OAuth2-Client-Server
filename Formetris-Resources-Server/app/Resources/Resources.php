<?php

namespace Resources;

use GuzzleHttp\Client;

class Resources {

    const OAUTH2_SERVER_BASE_URI = 'http://localhost:8000';
    const OAUTH2_SERVER_TOKEN_END_POINT = '/token.php';
    const TOKEN_HASH_SECRET_KEY = 'For!MeTrhriSs$Hhhh?';

    private $OAuth2StatusCode;

    public function __construct()
    {

    }

    private function initializeOAuth2ServerRequest() : object
    {
        return new Client(['base_uri' => self::OAUTH2_SERVER_BASE_URI]);
    }

    public function requestOAuth2ServerForToken($login, $password) : void
    {

/*
        $client = $this->initializeOAuth2ServerRequest();
        $response = $client->post(self::OAUTH2_SERVER_TOKEN_END_POINT);
*/

$this->OAuth2StatusCode = 401;

/*
        $this->OAuth2StatusCode = $response->getStatusCode();
        return $this->OAuth2StatusCode;
*/

/*
        $client = $this->initializeOAuth2ServerRequest();
        $request = $client->post(self::OAUTH2_SERVER_TOKEN_END_POINT, null, array(
            'client_id'     => $login,
            'client_secret' => $password,
            'grant_type'    => 'client_credentials',
        ));

        $response = $request->send();
        $responseBody = $response->getBody(true);
*/


/*
        $client = $this->initializeOAuth2ServerRequest();
        $response = $client->request(
            'POST',
            self::OAUTH2_SERVER_TOKEN_END_POINT,

            [
                'headers' => [
                    'Accept' => 'application/json',
                    'grant_type' => 'client_credentials'
                ]
            ],
            [
                'auth' => [$login, $password],
            ]
        );
*/

    }

    public function generateNewJsonTokenWithHash($token, $expirationTime) : bool
    {
        $token = 'mymarvelloustoken';
/*
password_hash($password, PASSWORD_BCRYPT);
json_encode()
*/
    }

    public function testTokenValidity(string $jsonToken) : bool
    {

        // -- Decode jsonToken

        /*
        json_decode()

        $token
        $expirationDate
        $hash
        */

        // -- Test jsonToken integrity

        // password_verify($password, $user['password']);

        // -- Test jsonToken validity date

        return false;
    }




    public function getOAuth2ServerBaseUri() : string
    {
        return self::OAUTH2_SERVER_BASE_URI;
    }

    public function getOAuth2ServerTokenEndPoint() : string
    {
        return self::OAUTH2_SERVER_TOKEN_END_POINT;
    }

    public function getOAuth2StatusCode() : int
    {
        return $this->OAuth2StatusCode;
    }
}