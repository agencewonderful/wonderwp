<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/06/2017
 * Time: 18:56
 */

namespace WonderWp\Framework\Notification;

class AdminNotificationTest extends \PHPUnit_Framework_TestCase
{

    public function testGetMarkupSuccessShouldBuildNotif()
    {
        $notification = new AdminNotification('success','testMessage');
        $mk = $notification->getMarkup();
        $this->assertNotEmpty($mk);
    }

    public function testGetMarkupDismissibleShouldBeDismissible()
    {
        $notification = new AdminNotification('success','testMessage');
        $notification->setDismissible(true);
        $mk = $notification->getMarkup();
        $this->assertTrue(stripos($mk,'is-dismissible')!==false);
    }
}
