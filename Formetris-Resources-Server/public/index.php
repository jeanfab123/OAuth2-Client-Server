<?php

/***********************************
* Author : FABULAS Jean-Pierre
*
* Creation date : 2019-01-07
*
* Slim Routes
*
***********************************/

require '../vendor/autoload.php';
require '../app/settings.php';

use \Slim\Http\Request;
use \Slim\Http\Response;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Resources\Resources;

$app = new \Slim\App;

// -- Get Resources

$app->get('/', function (Request $request, Response $response) {

    $resources = new Resources;

    if ($resources->testClientAuthorizationToAccessResources($request, $response)) {

        // -- Treatment and send answer


    } else {
        return $resources->getResourcesResponse();
    }

});


// -- Exec Slim

$app->run();