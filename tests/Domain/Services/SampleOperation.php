<?php


namespace Extasy\API\tests\Domain\Services;

use Extasy\API\Domain\Core\ApiOperation;
use Extasy\API\Infrastructure\IO\AbstractRequest;
class SampleOperation extends ApiOperation
{
    public function __construct(AbstractRequest $request)
    {
        parent::__construct($request);
    }

    protected function action() {
        $word1 = $this->request->getParam('word1');
        $word2 = $this->request->getParam('word2');
        return sprintf('%s %s', $word1, $word2);
    }
}