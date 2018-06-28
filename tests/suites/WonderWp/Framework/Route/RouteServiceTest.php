<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/06/2017
 * Time: 17:12
 */

namespace WonderWp\Framework\Route;

use WonderWp\Framework\AbstractPlugin\AbstractPluginManager;

class RouteServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AbstractRouteService
     */
    private $routeService;

    public function setUp()
    {
        $fakeManager = new FakeManager('wonderwp',1);
        $fakeManager->setConfig('testkey',['fr_FR'=>'/testurl/{testkey2}']);
        $this->routeService = new FakeRouteService();
        $this->routeService->setManager($fakeManager);
    }

    public function testGenerateUrlWithRouteAndParamsShouldGenerateParameterizedUrl(){
        $url = $this->routeService->generateUrl('testkey',['testkey2'=>'testval2'],'fr_FR');
        $this->assertEquals('/testurl/testval2',$url);
    }
}
class FakeRouteService2 extends AbstractRouteService
{
    public function getRoutes()
    {
        if (empty($this->routes)) {
            $this
                ->addCallableRoute('route_name_inmanager', 'controllerAction')
                ->addCallableRoute('/url-to-catch/{component}', 'controllerAction')
                ->addFileRoute('route_name_inmanager', 'file_to_redirect_to.php')
            ;
        }

        return $this->routes;
    }
}

class FakeManager2 extends AbstractPluginManager {
}
