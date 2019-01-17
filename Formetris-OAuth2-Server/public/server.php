<?php

/***********************************
* Author : FABULAS Jean-Pierre
*
* Creation date : 2019-01-09
*
* OAuth 2 Server file
*
***********************************/



/********************************************************/
/******* TO DO : A mettre dans un fichier de conf *******/
/********************************************************/

$dsn      = 'mysql:dbname=slim_oauth2;host=localhost';
$username = 'root';
$password = '';

/********************************************************/
/********************************************************/
/********************************************************/



// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)
require_once('../src/OAuth2/Autoloader.php');
OAuth2\Autoloader::register();



/*************************************************/
/******* TO DO : Intégrer à l'autoload ??? *******/
/*************************************************/

require_once('PdoApp.php');

/*********************************************/
/*********************************************/
/*********************************************/



// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
//$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
$storage = new PdoApp(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

// Pass a storage object or array of storage objects to the OAuth2 server class
//$server = new OAuth2\Server($storage);
$server = new OAuth2\Server($storage, array(
    'always_issue_new_refresh_token' => true,
    'auth_code_lifetime' => 30,
    'access_lifetime' => 120,
    'refresh_token_lifetime' => 300,
));

// Add the "Client Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

// Add the "User Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));

// Add the "Authorization Code" grant type (this is where the oauth magic happens)
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

// Add the "Refresh Token" grant type to your OAuth server
$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));
