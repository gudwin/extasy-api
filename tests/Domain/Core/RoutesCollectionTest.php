<?php
namespace Extasy\API\tests\Domain\Core;

use Extasy\API\tests\BaseTest;
use Extasy\API\tests\SampleRequest;
use Extasy\API\Domain\Core\Route;
use Extasy\API\Domain\Core\RoutesCollection;
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

        $route = new Route( $request,'','/other-path', [Route::GET_METHOD] );
        $this->collection->add( $route );
        $this->collection->findMatched();
    }
    public function testFindMatched() {
        $request = new SampleRequest([
            'path' => '/sample-path',
        ]);

        $route = new Route( $request,'','/sample-path', [Route::GET_METHOD] );
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

        $firstRoute = new Route( $request,'','/other-path', [Route::GET_METHOD],'first' );
        $this->collection->add( $firstRoute );
        $secondRoute = new Route( $request,'','/other-path', [Route::GET_METHOD],'second' );
        $this->collection->add( $secondRoute );

        $found = $this->collection->findByAlias('first');
        $this->assertEquals( $firstRoute, $found);
        $found = $this->collection->findByAlias('second');
        $this->assertEquals( $secondRoute, $found);
    }
}