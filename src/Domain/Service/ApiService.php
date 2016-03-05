<?php
namespace Extasy\API\Domain\Service;

use Extasy\API\Infrastructure\IO\AbstractRequest;
use Extasy\API\Infrastructure\IO\AbstractOutput;
use Extasy\API\Domain\Core\OutputError;
use Extasy\API\Domain\Core\RoutesCollection;
use Extasy\API\Domain\Exceptions\ApiException;


class ApiService
{
    /**
     * @var AbstractOutput
     */
    protected $output = null;
    /**
     * @var RoutesCollection
     */
    protected $routes = null;

    /**
     * @var AbstractRequest
     */
    protected $request = null;
    public function __construct( RoutesCollection $routes, AbstractRequest $request, AbstractOutput $output ) {
        $this->routes = $routes;
        $this->request = $request;
        $this->output = $output;
    }
    public function execute() {
        try {
            $route = $this->routes->findMatched( );
            $result = $route->getOperation();
            $result = $result->exec( );
        } catch (ApiException $e ) {
            // log message
            $result = null;
            $error = new OutputError(__CLASS__, (string)$e);
            $this->output->addError( $error );
        }
        return $this->output->execute($result);
    }
}