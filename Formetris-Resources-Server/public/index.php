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

    $resources = new Resources;

    if ($resources->testClientAuthorizationToAccessResources($request, $response)) {

        // -- Treatment and send answer


    } else {
        return $resources->getResourcesResponse();
    }

});


// -- Exec Slim

$app->run();