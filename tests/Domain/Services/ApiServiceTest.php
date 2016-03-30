<?php
namespace Extasy\API\tests\Domain\Services;

use Extasy\API\Domain\Core\ApiOperationFactory;
use Extasy\API\tests\BaseTest;
use Extasy\API\tests\SampleRequest;
use Extasy\API\tests\SampleOutput;
use Extasy\API\Domain\Route\RoutesCollection;
use Extasy\API\Domain\Route\Route;
use Extasy\API\Domain\Route\RouteConfig;
use Extasy\API\Domain\Service\ApiService;
use Extasy\API\Domain\Exceptions\NotFoundException;

class ApiServiceTest extends BaseTest
{

    public function testUnableToFindMatchingRoute()
    {
        $request = new SampleRequest();
        $output = new SampleOutput();
        $collection = new RoutesCollection();
        $service = new ApiService($collection, $request, $output);
        $service->execute();
        $this->assertTrue($output->hasErrors());
    }

    public function testExecute()
    {
        $request = new SampleRequest([
            'method' => Route::GET_METHOD,
            'params' => ['word2' => 'world!'],
            'path' => '/aaa/Hello'
        ]);
        $output = new SampleOutput();
        $collection = new RoutesCollection();

        $config = new RouteConfig();
        $config->request = $request;
        $config->operation = new ApiOperationFactory( function () use ($request) {
            return new SampleOperation( $request);
        } );
        $config->methods = Route::GET_METHOD;
        $config->path = '/aaa/:word1';

        $route = new Route( $config );
        $this->assertTrue( $route->match());
        $collection->add( $route );

        $service = new ApiService($collection, $request, $output);
        $result = $service->execute();
        $this->assertFalse($output->hasErrors());
        $this->assertEquals('Hello world!', $result);;
    }
}