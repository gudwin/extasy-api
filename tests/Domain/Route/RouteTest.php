<?php
namespace Extasy\API\tests\Domain\Route;

use Extasy\API\Domain\Route\RouteConfig;
use Extasy\API\tests\BaseTest;
use Extasy\API\tests\Domain\Core\SampleApi;
use Extasy\API\tests\SampleRequest;
use Extasy\API\Domain\Route\Route;
use Extasy\API\Domain\Core\ApiOperationFactory;


class RouteTest extends BaseTest
{
    const SAMPLE_PATH = '/my-path';

    /**
     * @var \Extasy\API\tests\SampleRequest
     */
    protected $request = null;

    public function setUp()
    {
        parent::setUp();
        $this->request = new SampleRequest([
            'method' => Route::GET_METHOD,
            'path' => self::SAMPLE_PATH
        ]);
    }

    public function testMatch()
    {
        $data = [
            [self::SAMPLE_PATH, Route::GET_METHOD, true],
            ['/my', Route::GET_METHOD, true],
            [self::SAMPLE_PATH, Route::POST_METHOD, false],
            ['/abrakadabra', Route::GET_METHOD, false],

        ];
        foreach ($data as $row) {
            $expectedResult = $row[2];

            $config = new RouteConfig();
            $config->path = $row[0];
            $config->methods = $row[1];
            $config->request = $this->request;

            $route = new Route($config);
            $result = $route->match();
            $this->assertEquals($expectedResult, $result, sprintf('%s:%s', $row[0], $row[1]));
        }
    }

    /**
     * @expectedException \Extasy\API\Domain\Exceptions\NotFoundException
     */
    public function testGetOperationWithUnknownAPI()
    {
        $config = new RouteConfig();
        $config->request = $this->request;
        $config->path = 'unknown';
        $config->methods = Route::GET_METHOD;

        $route = new Route($config);
        $route->getOperation();
    }

    public function testGetOperation()
    {
        $config = new RouteConfig();
        $config->request = $this->request;
        $config->methods = Route::GET_METHOD;
        $config->operation = new ApiOperationFactory(SampleApi::CLASS_NAME);

        $route = new Route($config);
        $api = $route->getOperation();
        $this->assertTrue($api instanceof SampleApi);
    }

    public function testRequestExtendedWith()
    {
        $request = new SampleRequest([
            'method' => Route::GET_METHOD,
            'path' => '/my/Brazil/toys/123'
        ]);
        $config = new RouteConfig();
        $config->request = $request;
        $config->operation = new ApiOperationFactory(SampleApi::CLASS_NAME);
        $config->path = '/my/:country/:category/:product';
        $config->methods = Route::GET_METHOD;
        $route = new Route($config);
        $route->getOperation();

        $this->assertEquals($request->getParam('country'), 'Brazil');
        $this->assertEquals($request->getParam('category'), 'toys');
        $this->assertEquals($request->getParam('product'), '123');

    }

    public function testWildcardsSupported()
    {
        $config = new RouteConfig();
        $request = new SampleRequest([
            'method' => Route::GET_METHOD,
            'path' => '/my/Brazil/toys/123'
        ]);

        $config->request = $request;
        $config->operation = new ApiOperationFactory(SampleApi::CLASS_NAME);
        $config->path = '/my/*';
        $config->methods = Route::GET_METHOD;

        $route = new Route($config);
        $route->getOperation();

        $this->assertEquals($request->getParam('param0'), 'Brazil');
        $this->assertEquals($request->getParam('param1'), 'toys');
        $this->assertEquals($request->getParam('param2'), '123');
    }

    public function testGetAlias()
    {
        $fixture = 'my-alias';

        $config = new RouteConfig();
        $config->request = $this->request;
        $config->path = self::SAMPLE_PATH;
        $config->alias = $fixture;

        $route = new Route($config);
        $this->assertEquals($fixture, $route->getAlias());
    }
}