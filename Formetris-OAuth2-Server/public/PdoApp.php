<?php

//namespace OAuth2\Storage;

/*
use OAuth2\OpenID\Storage\UserClaimsInterface;
use OAuth2\OpenID\Storage\AuthorizationCodeInterface as OpenIDAuthorizationCodeInterface;
use InvalidArgumentException;
*/

class PdoApp extends OAuth2\Storage\Pdo {

    // *********************************************************************************** //
    // This class override Pdo methods which contain password and secret_code to hash them //
    // However, "isPublicClient()" contains "secret_code" but is not concerned             //
    // *********************************************************************************** //

    /**
     * @param string $password
     * @return string
     */
    public function hashPassword($password)
    {

        $options = [
            'cost' => 15,
//            'memory_cost' => 2048,
            'time_cost' => 1000,
        ];
        return password_hash($password, PASSWORD_ARGON2I, $options);
    }

    /**
     * @param array $user
     * @param string $password
     * @return bool
     */
    public function checkPassword($user, $password)
    {

        return (password_verify($password, $user['password']));
    }

    /**
     * @param string $client_id
     * @param null|string $client_secret
     * @return bool
     */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        $stmt = $this->db->prepare(sprintf('SELECT * from %s where client_id = :client_id', $this->config['client_table']));
        $stmt->execute(compact('client_id'));
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result && password_verify($client_secret, $result['client_secret']);
    }

    /**
     * @param string $client_id
     * @param null|string $client_secret
     * @param null|string $redirect_uri
     * @param null|array  $grant_types
     * @param null|string $scope
     * @param null|string $user_id
     * @return bool
     */
    public function setClientDetails($client_id, $client_secret = null, $redirect_uri = null, $grant_types = null, $scope = null, $user_id = null)
    {

        if ($client_secret != null) {
            $client_secret_origin = $client_secret;
            $client_secret = $this->hashPassword($client_secret);
        }

        // -- if it exists, update it

        if ($this->getClientDetails($client_id)) {
            $stmt = $this->db->prepare($sql = sprintf('UPDATE %s SET client_secret=:client_secret, redirect_uri=:redirect_uri, grant_types=:grant_types, scope=:scope, user_id=:user_id where client_id=:client_id', $this->config['client_table']));
        } else {
            $stmt = $this->db->prepare(sprintf('INSERT INTO %s (client_id, client_secret, redirect_uri, grant_types, scope, user_id) VALUES (:client_id, :client_secret, :redirect_uri, :grant_types, :scope, :user_id)', $this->config['client_table']));
        }

        if ($client_secret_origin != null) {
            $client_secret = $client_secret_origin;
        }

        return $stmt->execute(compact('client_id', 'client_secret', 'redirect_uri', 'grant_types', 'scope', 'user_id'));
    }

}
