<?php


namespace Extasy\API\Domain\Core;

use Extasy\API\Infrastructure\IO\AbstractRequest;
use Extasy\API\Domain\Exceptions\ForbiddenException;
use Extasy\API\Domain\Exceptions\NotFoundException;

class Route
{
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';
    const PUT_METHOD = 'PUT';
    const DELETE_METHOD = 'DELETE';

    protected $alias = '';
    protected $path = '';
    protected $apiOperation = '';
    protected $methods = [];
    /**
     * @var AbstractRequest
     */
    protected $request = [];

    public function __construct(AbstractRequest $request, $apiOperation, $path, $methods, $alias = '')
    {
        $this->request = $request;
        $this->apiOperation = $apiOperation;
        $this->path = $path;
        if ( is_scalar( $methods )) {
            $methods = [ $methods ];
        }
        $this->methods = $methods;
        $this->alias = $alias;
    }

    /**
     * @return bool
     */
    public function match()
    {
        $result = $this->isMethodSupported($this->request->getMethod()) && $this->testPath($this->request->getPath());

        return $result;
    }

    /**
     * @return ApiOperation
     */
    public function getOperation()
    {
        $this->testIfOperationExists();

        $data = $this->parsePathVariables();

        $request = $this->request->extendWithParams( $data );

        $operation = new $this->apiOperation( $request );

        return $operation;

    }

    public function getAlias()
    {
        return $this->alias;
    }

    private function isMethodSupported($methodName)
    {
        return in_array($methodName, $this->methods);
    }

    private function getRegExp()
    {
        $result = '@' . $this->path . '@si';
        // Replace wildcard
        $result = str_replace('*', '(.*)', $result);
        //
        $result = preg_replace('/:(\w+)/', '([\w-\.]+)', $result);

        //
        return $result;
    }

    private function parsePathVariables()
    {
        $regExp = $this->getRegExp();
        preg_match($regExp, $this->request->getPath(), $matches);
        preg_match_all("/(\*)|:([\w-]+)/", $this->path, $argument_keys);
        //
        $params = $this->request->getParams();
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
        if (!class_exists($this->apiOperation)) {
            $error = sprintf('Api operation class `%s` not found ', $this->apiOperation);
            throw new NotFoundException($error);
        }
    }

    private function testPath($path)
    {
        return preg_match($this->getRegExp(), $path);
    }


}