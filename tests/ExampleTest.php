<?php

namespace Deviddev\BillingoApiV3Wrapper\Tests;

use Orchestra\Testbench\TestCase;
use Deviddev\BillingoApiV3Wrapper\BillingoApiV3WrapperServiceProvider;
use Deviddev\BillingoApiV3Wrapper\BillingoApiV3Wrapper as Billingo;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [BillingoApiV3WrapperServiceProvider::class];
    }

    public function testPartnerApi()
    {
        $billingo = new Billingo();
        $partnerApi = $billingo->api('Partner')->getApi();
        $partnerModel = $billingo->api('Partner')->model('PartnerUpsert');
        $this->assertInstanceOf('\Swagger\Client\Api\PartnerApi', $partnerApi);
    }
}
