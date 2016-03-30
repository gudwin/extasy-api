<?php


namespace Extasy\API\Domain\Core;

use \InvalidArgumentException;
use Extasy\API\Domain\Exceptions\NotFoundException;

class ApiOperationFactory
{
    protected $callable = null;

    function __construct($callable)
    {
        $valid = $this->validate($callable);
        if (!$valid) {
            $error = sprintf('Not an callable object - %s', print_r($callable, true));
            throw new InvalidArgumentException($error);
        }
        $this->callable = $callable;
    }

    protected function validate($callable)
    {
        $result = false;
        $result = $result || is_callable($callable);
        $result = $result || is_object($callable);
        $result = $result || (is_string($callable) && class_exists($callable));

        return $result;
    }

    public function get()
    {
        $result = null;
        if (is_object($this->callable)) {
            $result = $this->callable;
        }
        if (is_callable($this->callable)) {
            $result = call_user_func($this->callable);
        }
        if (is_string( $this->callable) && class_exists($this->callable)) {
            $result = new $this->callable;
        }
        if ($result instanceof ApiOperation) {
            return $result;
        }
        throw new NotFoundException('ApiOperationFactory failed to create an object');
    }


}
