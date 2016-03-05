<?php


namespace Extasy\API\tests\Domain\Core;

use Extasy\API\Domain\Core\ApiOperation;
class SampleApi extends ApiOperation
{
    const CLASS_NAME = 'Extasy\\API\\tests\\Domain\\Core\\SampleApi';
    protected $called = false;
    protected function action() {
        $this->called = true;
        return null;
    }
    public function addValidator( $validator ) {
        $this->validators[] = $validator;
    }
    public function isCalled() {
        return $this->called;
    }
}