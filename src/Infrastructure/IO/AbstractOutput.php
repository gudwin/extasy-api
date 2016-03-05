<?php

namespace Extasy\API\Infrastructure\IO;

use Extasy\API\Domain\Core\OutputError;

interface AbstractOutput {


    /**
     * @param $message
     * @param string $context
     */
    public function addError( OutputError $error );

    /**
     * @return boolean
     */
    public function hasErrors();

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @param $data
     * @return mixed
     */
    public function execute( $data );
}
