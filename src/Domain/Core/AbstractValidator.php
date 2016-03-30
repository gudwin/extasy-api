<?php


namespace Extasy\API\Domain\Core;


interface AbstractValidator
{
    /**
     * @throws \Exception
     * @return bool
     */
    public function validate();

}