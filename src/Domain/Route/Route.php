<?php


namespace Extasy\API\Domain\Route;

use Extasy\API\Domain\Core\ApiOperationFactory;
use Extasy\API\Infrastructure\IO\AbstractRequest;
use Extasy\API\Domain\Exceptions\ForbiddenException;
use Extasy\API\Domain\Exceptions\NotFoundException;

class Route
{
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';
    const PUT_METHOD = 'PUT';
    const DELETE_METHOD = 'DELETE';

    /**
     * @var RouteConfig
     */
    protected $config = null;

    public function __construct(RouteConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function match()
    {
        $result = $this->isMethodSupported($this->config->request->getMethod()) && $this->testPath($this->config->request->getPath());

        return $result;
    }

    /**
     * @return \Extasy\API\Domain\Core\ApiOperation
     */
    public function getOperation()
    {
        $this->testIfOperationExists();

        $data = $this->parsePathVariables();

        $this->config->request->extendWithParams($data);


        $operation = $this->config->operation->get();

        return $operation;

    }

    public function getAlias()
    {
        return $this->config->alias;
    }

    private function isMethodSupported($methodName)
    {
        return in_array($methodName, $this->config->methods);
    }


    private function parsePathVariables()
    {
        $regExp = $this->getRegExp();
        preg_match($regExp, $this->config->request->getPath(), $matches);
        preg_match_all("/(\*)|:([\w-]+)/", $this->config->path, $argument_keys);
        //
        $params = $this->config->request->getParams();
        // grab array with matches
        $argument_keys = $argument_keys[0];
        //
        $unnamedParamIndex = 0;

        // loop trough parameter names, store matching value in $params array
        foreach ($argument_keys as $key => $name) {
            $isWildcard = '*' == $name;
            if (!$isWildcard) {
                $name = substr($name, 1);
            }
            if (isset($matches[$key + 1])) {
                if (!$isWildcard) {
                    $params[$name] = $matches[$key + 1];
                } else {
                    $list = explode('/', $matches[$key + 1]);
                    foreach ($list as $row) {
                        $name = 'param' . $unnamedParamIndex;
                        $unnamedParamIndex++;
                        $params[$name] = $row;
                    }
                }
            }
        }

        return $params;
    }

    private function testIfOperationExists()
    {
        $valid = is_object($this->config->operation) && ($this->config->operation instanceof ApiOperationFactory);
        if ( !$valid ) {
            $error = sprintf('Api operation attribute not found ');
            throw new NotFoundException($error);
        }
    }

    private function testPath($path)
    {
        return preg_match($this->getRegExp(), $path);
    }

    private function getRegExp()
    {
        $result = '@' . $this->config->path . '@si';
        // Replace wildcard
        $result = str_replace('*', '(.*)', $result);
        //
        $result = preg_replace('/:(\w+)/', '([\w-\.]+)', $result);

        //
        return $result;
    }
}