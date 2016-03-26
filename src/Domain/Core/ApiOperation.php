<?php


namespace Extasy\API\Domain\Core;

use Extasy\API\Infrastructure\IO\AbstractRequest;
abstract class ApiOperation
{
    /**
     * @var AbstractValidator[]
     */
    protected $validators = [];

    /**
     * @var AbstractRequest
     */
    protected $request = null;

    public function __construct( AbstractRequest $request)
    {
        $this->request = $request;
    }

    public function exec( ) {
        foreach ( $this->validators as $validator) {
            $validator->validate( $this->request );
        }
        return $this->action();
    }
    public function addValidator( AbstractValidator $validator ) {
        $this->validators[] = $validator;
    }
    abstract protected function action();
}
