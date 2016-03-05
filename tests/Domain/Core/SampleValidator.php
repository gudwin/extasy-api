<?php


namespace Extasy\API\tests\Domain\Core;

use Extasy\API\Domain\Core\AbstractValidator;
use Extasy\API\Infrastructure\IO\AbstractRequest;
class SampleValidator implements AbstractValidator
{
    protected $called;
    public function validate(AbstractRequest $request)
    {
        $this->called = true;
    }
    public function isCalled() {
        return $this->called;
    }
}