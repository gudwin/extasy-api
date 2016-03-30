<?php


namespace Extasy\API\tests;

use \RuntimeException;
use Extasy\API\Domain\Exceptions\ApiException;
use Extasy\API\Infrastructure\IO\AbstractRequest;
use Extasy\API\Domain\Route\Route;

class SampleRequest implements AbstractRequest
{
    protected $params = [];
    protected $files = [];
    protected $path = null;
    protected $method = null;

    public function __construct($config = [])
    {
        $baseConfig = [
            'files' => [],
            'params' => [],
            'method' => Route::GET_METHOD,
            'path' => ''
        ];
        $config = array_merge($baseConfig, $config);

        $this->params = $config['params'];
        $this->files = $config['files'];
        $this->method = $config['method'];
        $this->path = $config['path'];
    }

    public function getParam($paramName)
    {
        if ( !isset( $this->params[$paramName])) {
            throw new RuntimeException(sprintf('Param `%s` not found', $paramName));
        }
        return $this->params[$paramName];
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getFile($fileName)
    {
        if ( !isset( $this->files[$fileName])) {
            throw new RuntimeException(sprintf('File `%s` not found', $fileName));
        }
        return $this->files[$fileName];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }
    public function extendWithParams($params)
    {
        $this->params = array_merge( $this->params, $params );
        return $this;
    }

}