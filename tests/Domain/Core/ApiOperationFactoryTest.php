<?php


namespace Extasy\API\tests\Domain\Core;

use Extasy\API\tests\BaseTest;
use Extasy\API\Domain\Core\ApiOperationFactory;
use Extasy\API\tests\SampleRequest;
class ApiOperationFactoryTest extends BaseTest
{
    public function testWithRealApiOperation() {
        $operation = new SampleApi( new SampleRequest());
        $factory = new ApiOperationFactory( $operation );
        $this->assertEquals( $operation, $factory->get());
    }
    public function testWithString() {
        $factory = new ApiOperationFactory( SampleApi::CLASS_NAME );
        $result = $factory->get();
        $this->assertTrue( $result instanceof SampleApi );
    }
    public function testWithCallback() {
        $function = function () {
            $request = new SampleRequest();
            return new SampleApi( $request );
        };
        $factory = new ApiOperationFactory( $function );
        $result = $factory->get();
        $this->assertTrue( $result instanceof SampleApi );
    }
}