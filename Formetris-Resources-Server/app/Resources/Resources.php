<?php

namespace Resources;

use GuzzleHttp\Client;

class Resources {

    const OAUTH2_SERVER_BASE_URI = 'http://localhost:8000';
    const OAUTH2_SERVER_TOKEN_END_POINT = '/token.php';
    // const TOKEN_HASH_SECRET_KEY = 'For!MeTrhriSs$Hhhh?'; // useless ?

    private $OAuth2StatusCode;
    private $oAuth2JsonToken;
    private $resourcesJsonToken;
    private $resourcesResponse;

    public function __construct()
    {

    }


    public function requestOAuth2ServerForToken(string $login, string $password) : void
    {

/*
        $client = $this->initializeOAuth2ServerRequest();
        $response = $client->post(self::OAUTH2_SERVER_TOKEN_END_POINT);
*/

$this->OAuth2StatusCode = 401;

$this->OAuthJsonToken = null;

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

    public function generateResourcesJsonToken(string $OAuth2ServerToken) : bool
    {

        // -- Decode JSON OAuth2 Server Token

        $serverToken = json_decode($OAuth2ServerToken);

        // -- Extract Token datas of transmitted OAuth2 Server Token

        $token = isset($serverToken->access_token) ? $serverToken->access_token : null;

        if ($token == null) {
            return false;
        }

        $expirationTime = isset($serverToken->expires_in) ? $serverToken->expires_in : null;

        // -- Calculate expiration date

        $expirationDate = date('Y-m-d H:i:s', strtotime(' + ' . $expirationTime . ' seconds'));

        // -- Generate Token hash

        $hash = $this->hashTokenWithExpirationDate($token, $expirationDate);

        // -- Build Resources Token

        $resourcesToken = [
            'token' => $token,
            'expiration_date' => $expirationDate,
            'hash' => $hash
        ];

        // -- Encode JSON

        $this->resourcesJsonToken = json_encode($resourcesToken);

        return true;
    }

    public function testClientAuthorization(string $jsonToken) : bool
    {

        $clientTokenDatas = $this->extractClientTokenDatas($jsonToken);

        if ($clientTokenDatas->token == null) {
            return false;
        }

        if ($clientTokenDatas->hash == null) {
            return false;
        }

        if (!$this->testClientTokenAuthenticity($clientTokenDatas)) {
            return false;
        }

        if (!$this->testClientTokenExpirationDate($clientTokenDatas)) {
            return false;
        }

        return true;
    }


    public function extractClientTokenDatas(string $jsonToken) : object
    {

        $tokenDatas = json_decode($jsonToken);

        $extractedTokenDatas = new \stdClass;

        $extractedTokenDatas->token = isset($tokenDatas->token) ? $tokenDatas->token : null;
        $extractedTokenDatas->expiration_date = isset($tokenDatas->expiration_date) ? $tokenDatas->expiration_date : null;
        $extractedTokenDatas->hash = isset($tokenDatas->hash) ? $tokenDatas->hash : null;

        return $extractedTokenDatas;
    }

    public function testClientTokenAuthenticity(object $clientTokenDatas) : bool
    {
        $token = isset($clientTokenDatas->token) ? $clientTokenDatas->token : null;
        $hash = isset($clientTokenDatas->hash) ? $clientTokenDatas->hash : null;
        $expirationDate = isset($clientTokenDatas->expiration_date) ? $clientTokenDatas->expiration_date : null;

        if (($token == null) || ($hash == null))
            return false;

        return password_verify($token.$expirationDate, $hash);
    }


    public function testClientTokenExpirationDate(object $clientTokenDatas) : bool
    {

        $expirationDate = isset($clientTokenDatas->expiration_date) ? $clientTokenDatas->expiration_date : null;

        if ($expirationDate < date('Y-m-d H:i:s')) {
            return false;
        }

        return true;
    }


    public function testClientAuthorizationToAccessResources(object $request, object $response)
    {

        // -- Get Token
    
        $token = $request->getHeader('token');
     
        $jsonToken = isset($token['token']) ? $token['token'] : null;
    
        // -- Token is not defined
    
        if ($jsonToken == null) {
    
            // -- Get Login and Password
    
            $auth = $response->getHeader('auth');
    
            $login = isset($auth[0]) ? $auth[0] : null;
            $password = isset($auth[1]) ? $auth[1] : null;
    
            if (($login != null) && ($password != null)) {
    
                // -- Request OAuth2 Server Token with Login and Password
    
                $this->requestOAuth2ServerForToken($login, $password);

                // -- Test OAuth2 Server request response

                if ($this->getOAuth2StatusCode() != 200) { // TO DO : à modifier ???
    
                    // -- Send Bad Response
    
                    $this->resourcesResponse = $response->withStatus(401);
                    return false;
    
                } else {
    
                    // -- Generate Resources JSON Token
    
                    if (!$this->generateResourcesJsonToken($this->getOAuth2JsonToken())) {
                        $this->resourcesResponse = $response->withStatus(401);
                        return false;
                    }
    
                    $resourcesJsonToken = $this->getResourcesToken();
                }
    
            } else {
    
                // -- Login and/or password not defined
    
                $this->resourcesResponse = $response->withStatus(401);
                return false;
    
            }
    
        // -- Token is defined
        
        } else {
    
            // -- Test Client authorization
    
            if (!$this->testClientAuthorization($jsonToken)) {
    
                // -- Send token error message
    
                $this->resourcesResponse = $response->withStatus(401);
                return false;
            }
        }

        $this->resourcesResponse = $response->withStatus(200);
        return true;
    }


    /**
    * Initialize OAuth2 Server Request
    *
    * @return object Client Object
    */
    private function initializeOAuth2ServerRequest() : object
    {
        return new Client(['base_uri' => self::OAUTH2_SERVER_BASE_URI]);
    }

    /**
    * Hash Token with expiration date
    *
    * @param string $token
    * @param date expirationDate
    *
    * @return string hash
    */
    private function hashTokenWithExpirationDate(string $token, string $expirationDate) : string
    {
        return password_hash($token . $expirationDate, PASSWORD_BCRYPT);
    }

    /**
    * Get OAuth2 Server Url
    *
    * @return string OAuth2 Server Url
    */
    public function getOAuth2ServerBaseUri() : string
    {
        return self::OAUTH2_SERVER_BASE_URI;
    }

    /**
    * Get OAuth2 Server Token end point url
    *
    * @return string OAuth2 Server end point url
    */
    public function getOAuth2ServerTokenEndPoint() : string
    {
        return self::OAUTH2_SERVER_TOKEN_END_POINT;
    }

    /**
    * Get OAuth2 Json Token
    *
    * @return string OAuth2 Json Token
    */
    public function getOAuth2JsonToken() : string
    {
        return $this->oAuth2JsonToken;
    }

    /**
    * Get OAuth2 Status Code
    *
    * @return string OAuth2 Status Code
    */
    public function getOAuth2StatusCode() : int
    {
        return $this->OAuth2StatusCode;
    }

    /**
    * Get Resources Json Token
    *
    * @return string Resources Json Token
    */
    public function getResourcesJsonToken() : string
    {
        return $this->resourcesJsonToken;
    }

    /**
    * Get Resources Response
    *
    * @return object Resources Response
    */
    public function getResourcesResponse() : object
    {
        return $this->resourcesResponse;
    }
}