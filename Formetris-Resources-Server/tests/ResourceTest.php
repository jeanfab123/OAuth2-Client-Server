<?php

namespace Testing;

use PHPUnit\Framework\TestCase;
use Resources\Resources;

class ResourceTest extends TestCase
{

    /**
     * @test
     */
    public function shouldFailWhenThereIsBadCredentials()
    {
        $resources = new resources;
        $resources->requestOAuth2ServerForToken('mybadlogin', 'mybadpassword');
        $this->assertEquals(401, $resources->getOAuth2StatusCode());
    }


    /**
     * @test
     */
    public function shouldValidWhenThereIsGoodCredentials()
    {
        $resources = new resources;
        $resources->requestOAuth2ServerForToken('mygoodlogin', 'mygoodpassword');
        $this->assertEquals(200, $resources->getOAuth2StatusCode());
    }


    /**
     * @test
     */
    public function shouldFailWhenTokenIsInvalid() {

        // -- Bad Token
        $token = [
            'token' => 'mybadtoken',
            'max-validity-date' => '2019-01-01 12:24:37',
            'hash' => '$12.hzez$zoduy$ncsolasotwnc'
        ];

        $jsonToken = json_encode($token);

        $resources = new resources;
        $tokenResult = $resources->testTokenValidity($jsonToken);
        $this->assertFalse($tokenResult);
    }

    /**
     * @test
     */
    public function shouldValidWhenTokenIsValid() {

        // -- Good Token
        $token = [
            'token' => 'mygoodtoken',
            'max-validity-date' => '2019-01-01 12:24:37',
            'hash' => '$12.hzez$zoduy$ncsolasotwnc'
        ];

        $jsonToken = json_encode($token);

        $resources = new resources;
        $tokenResult = $resources->testTokenValidity($jsonToken);
        $this->assertTrue($tokenResult);
    }

    // --------------------------------------------------- //
    // -- TO DO : tester que le token est correctement généré
    // --------------------------------------------------- //



}