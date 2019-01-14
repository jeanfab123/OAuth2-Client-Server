<?php

require '../vendor/autoload.php';
require '../app/settings.php';

use \Slim\Http\Request;
use \Slim\Http\Response;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Resources\Resources;

$app = new \Slim\App;

// -- Survey Generation

$app->get('/survey-generation', function (Request $request, Response $response) {

/*
$response = $response->withStatus(401);
return $response;
*/

    $resources = new Resources;

    // -- Get Body

    $token = $request->getHeader('token');

    // -- Get Token
 
    $jsonToken = isset($token['token']) ? $token['token'] : null;

    // -- Token is not defined

    if ($jsonToken == null) {

        // -- Get Login and Password

        $auth = $response->getHeader('auth');

        $login = isset($auth[0]) ? $auth[0] : null;
        $password = isset($auth[1]) ? $auth[1] : null;

        if (($login != null) && ($password != null)) {

            // -- Request OAuth2 Server Token with Login and Password

            $resources->requestOAuth2ServerForToken($login, $password);

            // -- Test OAuth2 Server request response

            if ($resources->getOAuth2StatusCode() != 200) {

                // -- Send Bad Response

                $response = $response->withStatus(401);
                return $response;

            } else {

                // -- Generate new JSON Token (with hash)

                $newJsonToken = $resources->generateNewJsonTokenWithHash($oAuth2JsonToken);
            }

        } else {

            // -- Login and/or password not defined

            $response = $response->withStatus(401);
            return $response;

        }

    // -- Token is defined
    
    } else {

        // -- Test token validity

        if (!$resources->testTokenValidity($jsonToken)) {

            // -- Send token error message

            $response = $response->withStatus(401);
            return $response;

        }
    }

    // -- Treatment and send answer

});

// -- Exec Slim

$app->run();