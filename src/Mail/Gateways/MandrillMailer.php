<?php

namespace WonderWp\Mail\Gateways;

use WonderWp\API\Result;
use WonderWp\DI\Container;
use WonderWp\Mail\AbstractMailer;

/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 07/11/2016
 * Time: 17:55
 */
class MandrillMailer extends AbstractMailer
{

    /** @var  \Mandrill  */
    private $_mandrill;

    /**
     * @param array $opts
     * @return Result
     */
    public function send($opts=array())
    {
        $container = Container::getInstance();
        if(empty($this->_mandrill)) {
            $this->_mandrill = new \Mandrill($container->offsetGet('mandrill_api_key'));
        }

        $jsonPayLoad = $this->computeJsonPayload($opts);
        //\WonderWp\trace($jsonPayLoad);

        $endPointUrl = '/messages/send';

        $body = $this->getBody();
        if(strpos($body,'template::')!==false){
            $endPointUrl = '/messages/send-template';
        }

        $res = $this->_mandrill->call($endPointUrl,$jsonPayLoad);

        $successes = array();
        $failures = array();

        if(!empty($res)){
            foreach($res as $sentTo){
                if(!empty($sentTo['status']) && in_array($sentTo['status'],array("sent", "queued", "scheduled"))){
                    $successes[] = $sentTo;
                } else {
                    $failures[] = $sentTo;
                }
            }
        }

        $code = 500;
        if(!empty($successes)){
            $code = 200;
        }

        $result = new Result($code,array('res'=>$res,'successes'=>$successes,'failures'=>$failures));
        return $result;
    }

    public function computeJsonPayload($opts){

        $body = $this->getBody();
        $template = null;
        if(strpos($body,'template::')!==false){
            $template = str_replace('template::','',$body);
            $body = null;
        }

        $defaultOpts = array (
            'key' => $this->_mandrill->apikey,
            'message' =>
                array (
                    'html' => $body,
                    'text' => $this->getAltBody(),
                    'subject' => $this->getSubject(),
                    'from_email' => $this->_from[0],
                    'from_name' => $this->_from[1],
                    'to' => array (), //set further down
                    'important' => false,
                    'track_opens' => true,
                    'track_clicks' => true,
                    'auto_text' => true,
                    'auto_html' => false,
                    'inline_css' => true,
                    'url_strip_qs' => false,
                    'preserve_recipients' => NULL,
                    'view_content_link' => NULL,
                    //'bcc_address' => 'message.bcc_address@example.com',
                    'tracking_domain' => NULL,
                    'signing_domain' => NULL,
                    'return_path_domain' => NULL,
                    'merge' => true,
                    'merge_language' => 'mailchimp',
                    'global_merge_vars' =>
                        array (
                            /*0 =>
                                array (
                                    'name' => 'merge1',
                                    'content' => 'merge1 content',
                                ),*/
                        ),
                    'merge_vars' =>
                        array (
                            /*0 =>
                                array (
                                    'rcpt' => 'recipient.email@example.com',
                                    'vars' =>
                                        array (
                                            0 =>
                                                array (
                                                    'name' => 'merge2',
                                                    'content' => 'merge2 content',
                                                ),
                                        ),
                                ),*/
                        ),
                    /*'tags' =>
                        array (
                            0 => 'password-resets',
                        ),
                    'subaccount' => 'customer-123',
                    'google_analytics_domains' =>
                        array (
                            0 => 'example.com',
                        ),
                    'google_analytics_campaign' => 'message.from_email@example.com',*/
                    'metadata' =>
                        array (
                            'website' => get_bloginfo('url'),
                        ),
                    'recipient_metadata' =>
                        array (
                            /*0 =>
                                array (
                                    'rcpt' => 'recipient.email@example.com',
                                    'values' =>
                                        array (
                                            'user_id' => 123456,
                                        ),
                                ),*/
                        ),
                    'attachments' =>
                        array (
                            /*0 =>
                                array (
                                    'type' => 'text/plain',
                                    'name' => 'myfile.txt',
                                    'content' => 'ZXhhbXBsZSBmaWxl',
                                ),*/
                        ),
                    'images' =>
                        array (
                           /* 0 =>
                                array (
                                    'type' => 'image/png',
                                    'name' => 'IMAGECID',
                                    'content' => 'ZXhhbXBsZSBmaWxl',
                                ),*/
                        ),
                ),
            'async' => false,
            'ip_pool' => 'Main Pool',
            'send_at' => date('Y-m-d H:i:s'),
        );

        //template ?
        if(!empty($template)){
            $defaultOpts['template_name'] = $template;
            $defaultOpts['template_content'] = array(

            );
        }

        //Add recipients
        if(!empty($this->_to)){
            foreach($this->_to as $to){
                $defaultOpts['message']['to'][] = array(
                    'email'=>$to[0],
                    'name'=>$to[1],
                    'type'=>'to'
                );
            }
        }

        //reply to
        if(!empty($this->_reply_to)){
            $defaultOpts['message']['headers']['Reply-To'] = $this->_reply_to[0];
        }

        $payload = \WonderWp\array_merge_recursive_distinct($defaultOpts,$opts);

        return $payload;
    }

}