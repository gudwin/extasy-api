<?php


namespace Extasy\API\Domain\Route;

use Extasy\API\Infrastructure\IO\AbstractRequest;
use Extasy\API\Domain\Exceptions\NotFoundException;

class RoutesCollection
{
    /**
     * @var Route[]
     */
    protected $routes = [];

    public function add(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     *
     * @return Route
     */
    public function findMatched()
    {
        foreach ($this->routes as $route) {
            if ($route->match()) {
                return $route;
            }
        }
        throw new NotFoundException('Unable to find API operation for current request');
    }

    /**
     * @param $alias
     * @return Route
     */
    public function findByAlias($alias)
    {
        foreach ($this->routes as $route) {
            if ($route->getAlias() === $alias) {
                return $route;
            }
        }
        throw new NotFoundException('Unable to find API operation for alias - '. $alias );
    }
}