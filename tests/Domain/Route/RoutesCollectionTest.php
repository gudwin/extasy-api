<?php
namespace Extasy\API\tests\Domain\Route;

use Extasy\API\tests\BaseTest;
use Extasy\API\tests\SampleRequest;
use Extasy\API\Domain\Route\Route;
use Extasy\API\Domain\Route\RouteConfig;
use Extasy\API\Domain\Route\RoutesCollection;
class RoutesCollectionTest extends BaseTest
{
    /**
     * @var RoutesCollection
     */
    protected $collection = null;

    /**
     * @var null
     */
    protected $request = null;
    public function setUp() {
        parent::setUp();
        $this->collection = new RoutesCollection();

    }

    /**
     * @expectedException \Extasy\API\Domain\Exceptions\NotFoundException
     */
    public function testFindWhenEmpty() {
        $this->collection->findMatched();
    }
    /**
     * @expectedException \Extasy\API\Domain\Exceptions\NotFoundException
     */
    public function testFindFailed() {
        $request = new SampleRequest([
            'path' => '/sample-path',
        ]);
        $config = new RouteConfig();
        $config->request = $request;
        $config->methods = [Route::GET_METHOD];
        $config->path = '/other-path';

        $route = new Route( $config );
        $this->collection->add( $route );
        $this->collection->findMatched();
    }
    public function testFindMatched() {
        $request = new SampleRequest([
            'path' => '/sample-path',
        ]);
        $config = new RouteConfig();
        $config->request = $request;
        $config->path = '/sample-path';
        $config->methods = [Route::GET_METHOD];
        $route = new Route( $config );
        $this->collection->add( $route );

        $foundRoute = $this->collection->findMatched();
        $this->assertEquals( $route, $foundRoute );
    }
    /**
     * @expectedException \Extasy\API\Domain\Exceptions\NotFoundException
     */
    public function testFindByUnknownAlias() {
        $this->collection->findByAlias('my_alias');
    }
    public function testFindByAlias() {
        $request = new SampleRequest([
            'path' => '/',
        ]);
        $firstConfig = new RouteConfig();
        $firstConfig->request = $request;
        $firstConfig->path = '/other-path';
        $firstConfig->methods = [Route::GET_METHOD];
        $firstConfig->alias = 'first';


        $firstRoute = new Route( $firstConfig);
        $this->collection->add( $firstRoute );

        $secondConfig = new RouteConfig();
        $secondConfig->request = $request;
        $secondConfig->path = '/other-path2';
        $secondConfig->methods = [Route::GET_METHOD];
        $secondConfig->alias = 'second';


        $secondRoute = new Route( $secondConfig  );
        $this->collection->add( $secondRoute );

        $found = $this->collection->findByAlias('first');
        $this->assertEquals( $firstRoute, $found);
        $found = $this->collection->findByAlias('second');
        $this->assertEquals( $secondRoute, $found);
    }
}