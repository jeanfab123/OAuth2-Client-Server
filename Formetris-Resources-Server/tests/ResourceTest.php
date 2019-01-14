<?php

namespace Testing;

use PHPUnit\Framework\TestCase;
use Resources\Resources;

class ResourceTest extends TestCase
{

    private $OAuth2ServerToken = '{"access_token":"f559d449d2176ff40a20673524a6df1b0ce13413","expires_in":120,"token_type":"Bearer","scope":null}';

    private $OAuth2GoodLogin = 'testClient';
    private $OAuth2GoodPassword = ''; // TO DO : à compléter avec la bonne valeur

    private $OAuth2BadLogin = 'mybadlogin';
    private $OAuth2BadPassword = 'mybadpassword';


    /******************************************************************/
    /******************* TEST BAD / GOOD CREDENTIALS ******************/
    /******************************************************************/

    /**
     * @test
     */
    public function shouldFailWhenThereIsBadCredentials()
    {
        $resources = new Resources;
        $resources->requestOAuth2ServerForToken($this->OAuth2BadLogin, $this->OAuth2BadPassword);
        $this->assertEquals(401, $resources->getOAuth2StatusCode());
    }


    /**
     * @test
     */
    public function shouldValidWhenThereIsGoodCredentials()
    {
        $resources = new Resources;
        $resources->requestOAuth2ServerForToken($this->OAuth2GoodLogin, $this->OAuth2GoodPassword);
        $this->assertEquals(200, $resources->getOAuth2StatusCode());
    }


    /*********************************************************/
    /******************* TEST TOKEN FAILURE ******************/
    /*********************************************************/

    /**
     * @test
     */
    public function shouldFailWhenBadResourcesTokenAuthenticity() {

        $resources = new Resources;

        $resources->generateResourcesJsonToken($this->OAuth2ServerToken);
        $extractedTokenDatas = $resources->extractClientTokenDatas($resources->getResourcesJsonToken());

        // -- Modify token

        $extractedTokenDatas->token = 'isitabadtoken?noofcourse!!!!';

        $testResult = $resources->testClientTokenAuthenticity($extractedTokenDatas);

        $this->assertFalse($testResult);
    }

    /**
     * @test
     */
    public function shouldFailWhenResourcesTokenWasExpired() {

        $resources = new Resources;

        $resources->generateResourcesJsonToken($this->OAuth2ServerToken);
        $extractedTokenDatas = $resources->extractClientTokenDatas($resources->getResourcesJsonToken());

        // -- Modify expiration date

        $extractedTokenDatas->expiration_date = '2018-01-01 09:00:00';

        $testResult = $resources->testClientTokenExpirationDate($extractedTokenDatas);

        $this->assertFalse($testResult);
    }

    /*************************************************************/
    /******************* TEST TOKEN SUCCESSFULL ******************/
    /*************************************************************/

    /**
     * @test
     */
    public function shouldValidWhenResourcesServerIsCorrectlyGenerated() {

        // TO DO : Test à modifier ???

        $resources = new Resources;
        $testResult = $resources->generateResourcesJsonToken($this->OAuth2ServerToken);
        $this->assertTrue($testResult);
    }

    /**
     * @test
     */
    public function shouldValidWhenResourcesTokenIsValid() {

        $resources = new Resources;

        $resources->generateResourcesJsonToken($this->OAuth2ServerToken);

        $testResult = $resources->testClientAuthorization($resources->getResourcesJsonToken());
        $this->assertTrue($testResult);
    }

}