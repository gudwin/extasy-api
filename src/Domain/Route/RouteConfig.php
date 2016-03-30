<?php


namespace Extasy\API\Domain\Route;

/**
 * Class RouteConfig
 * @package Extasy\API\Domain\Core
 * @property array methods
 */
class RouteConfig
{
    /**
     * @var \Extasy\API\Infrastructure\IO\AbstractRequest
     */
    public $request;
    /**
     * @var \Extasy\API\Domain\Core\ApiOperationFactory
     */
    public $operation;

    public $alias;

    public $path;

    protected $methods;

    public function __set( $key, $value ) {
        $key = strtolower( $key );
        if ( 'methods' == $key) {
            if ( !is_array( $value )) {
                $value = [ $value ];
            }
            $this->methods = $value;
        }
    }
    public function __get( $key ) {
        $key = strtolower( $key );
        if ( 'methods' == $key) {
            return $this->methods;
        }
    }

}