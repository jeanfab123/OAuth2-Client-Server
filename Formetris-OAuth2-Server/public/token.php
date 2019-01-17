<?php

/***********************************
* Author : FABULAS Jean-Pierre
*
* Creation date : 2019-01-09
*
* OAuth 2 Server token file
*
***********************************/

// include our OAuth2 Server object
require_once __DIR__.'/server.php';

// Handle a request for an OAuth2.0 Access Token and send the response to the client
$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();