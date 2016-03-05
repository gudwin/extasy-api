<?php
namespace Extasy\API\Infrastructure\Service;

interface LoggableServiceInterface {
    public function add( $category, $errorMsg );
}