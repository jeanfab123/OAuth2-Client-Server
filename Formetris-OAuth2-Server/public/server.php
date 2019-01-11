<?php

$dsn      = 'mysql:dbname=slim_oauth2;host=localhost';
$username = 'root';
$password = '';

// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)
require_once('../src/OAuth2/Autoloader.php');
OAuth2\Autoloader::register();





// ************************************** //
// ***** A DEPLACER DANS UNE CLASSE ***** //
// ************************************** //

class appPDO extends OAuth2\Storage\Pdo {

    public function hashPassword($password) {
/*
print password_hash($password, PASSWORD_BCRYPT);
exit;
*/
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function checkPassword($user, $password)
    {
/*
echo $password . "***" . $user['password'];
exit;
*/
        return (password_verify($password, $user['password']));
        //return $user['password'] == $this->hashPassword($password);
    }
}




// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
//$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
$storage = new appPDO(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

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
