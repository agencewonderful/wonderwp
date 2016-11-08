<?php

namespace WonderWp\Mail\Gateways;

use WonderWp\Mail\AbstractMailer;

/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 07/11/2016
 * Time: 17:55
 */
class MandrillMailer extends AbstractMailer
{
    public function send()
    {
        return false;
    }

}