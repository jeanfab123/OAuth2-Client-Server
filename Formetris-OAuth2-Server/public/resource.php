<?php

/***********************************
* Author : FABULAS Jean-Pierre
*
* Creation date : 2019-01-09
*
* OAuth 2 Server resource
*
***********************************/

// include our OAuth2 Server object
require_once __DIR__.'/server.php';

// Handle a request to a resource and authenticate the access token
if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}
echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));