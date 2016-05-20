<?php


namespace Extasy\API\Domain\Core;


class OutputError
{
    protected $context = '';
    protected $message = '';

    public function __construct($context, $message)
    {
        $this->context = $context;
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function __toString()
    {
        return sprintf('[%s] %s', $this->context, $this->message);
    }
}