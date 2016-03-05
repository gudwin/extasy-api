<?php


namespace Extasy\API\Domain\Core;

use Extasy\API\Infrastructure\IO\AbstractRequest;

interface AbstractValidator
{
    /**
     * @param \Extasy\API\Infrastructure\IO\AbstractRequest $request
     * @return bool
     */
    public function validate(AbstractRequest $request);

}