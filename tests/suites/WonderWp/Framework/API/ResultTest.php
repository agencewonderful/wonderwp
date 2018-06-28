<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 22/06/2017
 * Time: 13:28
 */

namespace WonderWp\Framework\API;

/**
 * Class ResultTest
 * @package WonderWp\Framework\API
 * @see Result
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{

    public function testGetDataWithNoKeyShouldReturnAllValues()
    {
        $expected = ['msg'=>'test msg'];
        $result = new Result(200,$expected);
        $this->assertEquals($expected,$result->getData());
    }

    public function testGetDataWithKeyShouldReturnAssociatedValue()
    {
        $data = ['msg'=>'test msg'];
        $result = new Result(200,$data);
        $expected = $data['msg'];
        $this->assertEquals($expected,$result->getData('msg'));
    }
}
