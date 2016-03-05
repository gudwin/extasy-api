<?php
namespace Extasy\API\tests\Domain\Core;

use Extasy\API\tests\BaseTest;
use Extasy\API\tests\SampleRequest;
use Extasy\API\Domain\Core\Route;


class RouteTest extends BaseTest
{
    const SAMPLE_PATH = '/my-path';

    /**
     * @var \Extasy\API\tests\SampleRequest
     */
    protected $request = null;
    public function setUp() {
        parent::setUp();
        $this->request = new SampleRequest([
            'method' => Route::GET_METHOD,
            'path' => self::SAMPLE_PATH
        ]);
    }
    public function testMatch() {
        $data = [
            [self::SAMPLE_PATH, Route::GET_METHOD, true ],
            ['/my', Route::GET_METHOD, true ],
            [self::SAMPLE_PATH, Route::POST_METHOD, false],
            ['/abrakadabra', Route::GET_METHOD, false],

        ];
        foreach ( $data as $row ) {
            $expectedResult = $row[ 2 ];
            $route = new Route( $this->request, '',$row[0],$row[1]);
            $result = $route->match();
            $this->assertEquals( $expectedResult, $result, sprintf('%s:%s', $row[0], $row[1]) );
        }
    }
    /**
     * @expectedException \Extasy\API\Domain\Exceptions\NotFoundException
     */
    public function testGetOperationWithUnknownAPI() {
        $route = new Route( $this->request, 'unknown','',Route::GET_METHOD);
        $route->getOperation();
    }

    public function testGetOperation() {

        $route = new Route( $this->request, SampleApi::CLASS_NAME, '',Route::GET_METHOD);
        $api = $route->getOperation();
        $this->assertTrue( $api instanceof SampleApi );
    }
    public function testRequestExtendedWith() {
        $request = new SampleRequest([
            'method' => Route::GET_METHOD,
            'path' => '/my/Brazil/toys/123'
        ]);
        $route = new Route( $request, SampleApi::CLASS_NAME, '/my/:country/:category/:product',Route::GET_METHOD);
        $route->getOperation();

        $this->assertEquals( $request->getParam('country') ,'Brazil');
        $this->assertEquals( $request->getParam('category') ,'toys');
        $this->assertEquals( $request->getParam('product') ,'123');

    }
    public function testWildcardsSupported() {
        $request = new SampleRequest([
            'method' => Route::GET_METHOD,
            'path' => '/my/Brazil/toys/123'
        ]);
        $route = new Route( $request, SampleApi::CLASS_NAME, '/my/*',Route::GET_METHOD);
        $route->getOperation();

        $this->assertEquals( $request->getParam('param0') ,'Brazil');
        $this->assertEquals( $request->getParam('param1') ,'toys');
        $this->assertEquals( $request->getParam('param2') ,'123');
    }
    public function testGetAlias() {
        $fixture = 'my-alias';
        $route = new Route( $this->request, self::SAMPLE_PATH, '',Route::GET_METHOD, $fixture );
           $this->assertEquals( $fixture, $route->getAlias());
    }
}