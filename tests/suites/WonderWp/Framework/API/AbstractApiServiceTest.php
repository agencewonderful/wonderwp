<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/06/2017
 * Time: 14:53
 */

namespace WonderWp\Framework\API;

use WonderWp\Framework\HttpFoundation\Request;

class AbstractApiServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractApiService
     */
    private $apiService;

    public function setUp()
    {
        $request = Request::getInstance();
        $request->request->set('key1', 'value1');
        $request->request->set('key2', 'value2');
        $this->apiService = new FakeApiService();
        $this->apiService->setRequest($request);
    }

    public function testRequestParameterWithAllKey()
    {
        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $this->assertEquals($expected, $this->apiService->requestParameter('all'));
    }

    public function testRequestParameterWithSpecificKey()
    {
        $this->assertEquals('value1', $this->apiService->requestParameter('key1'));
    }
}

class FakeApiService extends AbstractApiService
{
}
