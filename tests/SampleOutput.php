<?php


namespace Extasy\API\tests;


use Extasy\API\Domain\Core\OutputError;
use Extasy\API\Infrastructure\IO\AbstractOutput;
class SampleOutput implements AbstractOutput
{
    protected $errors = [];

    /**
     * @param $message
     * @param string $context
     */
    public function addError( OutputError $error ) {
        $this->errors[] = $error;
    }

    /**
     * @return boolean
     */
    public function hasErrors() {
        return count( $this->errors ) > 0;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function execute( $data ) {
        return $data;
    }

}