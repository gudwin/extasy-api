<?php


namespace Extasy\API\Domain\Core;

use Extasy\API\Infrastructure\IO\AbstractRequest;
abstract class ApiOperation
{
    /**
     * @var AbstractValidator[]
     */
    protected $validators = [];

    public function __construct( )
    {
    }

    public function exec( ) {
        foreach ( $this->validators as $validator) {
            $validator->validate( );
        }
        return $this->action();
    }
    public function addValidator( AbstractValidator $validator ) {
        $this->validators[] = $validator;
    }
    abstract protected function action();
}
