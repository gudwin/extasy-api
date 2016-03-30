<?php


namespace Extasy\API\Domain\Validators;

class FieldsValidatorConfig
{
    /**
     * @var \Extasy\API\Infrastructure\IO\AbstractRequest
     */
    public $request = null;
    public $fields = [];
    public $files = [];
}