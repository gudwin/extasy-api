<?php


namespace Extasy\API\tests\Domain\Core;

use Extasy\API\Domain\Validators\FieldsValidator;
use Extasy\API\tests\BaseTest;
use Extasy\API\tests\SampleRequest;

class ApiOperationTest extends BaseTest
{
    public function testValidatorsCalled() {
        $request = new SampleRequest();
        $validator = new SampleValidator();
        $api = new SampleApi( $request );
        $api->addValidator( $validator );
        $api->exec();
        $this->assertTrue( $validator->isCalled() );
        $this->assertTrue( $api->isCalled() );
    }

}