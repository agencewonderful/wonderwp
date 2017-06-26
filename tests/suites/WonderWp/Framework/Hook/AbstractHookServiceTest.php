<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/06/2017
 * Time: 15:03
 */

namespace WonderWp\Framework\Hook;

class AbstractHookServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var  AbstractHookService */
    private $hookService;

    public function setUp()
    {
        $this->hookService = new FakeHookService();
    }

    public function testLoadTextDomain()
    {
        // ex: $this->assertEquals(false, $this->hookService->loadTextdomain('fakeDomain', 'fr_FR', '/fake/folder/path'));
    }
}

class FakeHookService extends AbstractHookService
{
    public function run()
    {
    }
}
