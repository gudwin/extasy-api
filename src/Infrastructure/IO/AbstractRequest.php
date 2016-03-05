<?php


namespace Extasy\API\Infrastructure\IO;


interface AbstractRequest
{
    /**
     * @param $paramName
     * @return mixed
     * @throw \Extasy\Api\Domain\Exceptions\ValidateException
     */
    public function getParam( $paramName );
    public function getParams( );

    /**
     * @param $fileName
     * @return mixed
     * @throw \Extasy\Api\Domain\Exceptions\ValidateException
     */
    public function getFile( $fileName );
    public function getMethod();
    public function getPath();
    public function extendWithParams( $params );
}