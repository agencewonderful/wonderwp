<?php

namespace WonderWp\Framework\Mail\Gateways;

use WonderWp\Framework\API\Result;
use function WonderWp\Framework\array_merge_recursive_distinct;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Mail\AbstractMailer;

class MandrillMailer extends AbstractMailer
{
    /** @var \Mandrill */
    protected $mandrill;

    public function __construct() {
        parent::__construct();
        if (empty($this->mandrill)) {
            $this->mandrill = new \Mandrill(Container::getInstance()->offsetGet('mandrill_api_key'));
        }
    }

    /**
     * @param array $opts
     *
     * @return Result
     */
    public function send(array $opts = [])
    {
        $container = Container::getInstance();

        $jsonPayLoad = $this->computeJsonPayload($opts);

        $endPointUrl = '/messages/send';

        $body = $this->getBody();
        if (strpos($body, 'template::') !== false) {
            $endPointUrl = '/messages/send-template';
        }

        $res = $this->mandrill->call($endPointUrl, $jsonPayLoad);

        $successes = [];
        $failures  = [];

        if (!empty($res)) {
            foreach ($res as $sentTo) {
                if (!empty($sentTo['status']) && in_array($sentTo['status'], ["sent", "queued", "scheduled"])) {
                    $successes[] = $sentTo;
                } else {
                    $failures[] = $sentTo;
                }
            }
        }

        $code = 500;
        if (!empty($successes)) {
            $code = 200;
        }

        $result = new Result($code, ['res' => $res, 'successes' => $successes, 'failures' => $failures]);

        return apply_filters('wwp.mailer.send.result',$result);
    }

    /**
     * Opts to json payload
     * @param $opts
     *
     * @return array
     */
    public function computeJsonPayload($opts)
    {

        $body     = $this->getBody();
        $template = null;
        if (strpos($body, 'template::') !== false) {
            $template = str_replace('template::', '', $body);
            $body     = null;
        }

        $defaultOpts = [
            'key'     => $this->mandrill->apikey,
            'message' =>
                [
                    'html'                => $body,
                    'text'                => $this->getAltBody(),
                    'subject'             => $this->getSubject(),
                    'from_email'          => $this->from[0],
                    'from_name'           => $this->from[1],
                    'to'                  => [], //set further down
                    'important'           => false,
                    'track_opens'         => true,
                    'track_clicks'        => true,
                    'auto_text'           => true,
                    'auto_html'           => false,
                    'inline_css'          => true,
                    'url_strip_qs'        => false,
                    'preserve_recipients' => null,
                    'view_content_link'   => null,
                    //'bcc_address' => 'message.bcc_address@example.com',
                    'tracking_domain'     => null,
                    'signing_domain'      => null,
                    'return_path_domain'  => null,
                    'merge'               => true,
                    'merge_language'      => 'mailchimp',
                    'global_merge_vars'   =>
                        [
                            /*0 =>
                                array (
                                    'name' => 'merge1',
                                    'content' => 'merge1 content',
                                ),*/
                        ],
                    'merge_vars'          =>
                        [
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
                        ],
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
                    'metadata'            =>
                        [
                            'website' => get_bloginfo('url'),
                        ],
                    'recipient_metadata'  =>
                        [
                            /*0 =>
                                array (
                                    'rcpt' => 'recipient.email@example.com',
                                    'values' =>
                                        array (
                                            'user_id' => 123456,
                                        ),
                                ),*/
                        ],
                    'attachments'         =>
                        [
                            /*0 =>
                                array (
                                    'type' => 'text/plain',
                                    'name' => 'myfile.txt',
                                    'content' => 'ZXhhbXBsZSBmaWxl',
                                ),*/
                        ],
                    'images'              =>
                        [
                            /* 0 =>
                                 array (
                                     'type' => 'image/png',
                                     'name' => 'IMAGECID',
                                     'content' => 'ZXhhbXBsZSBmaWxl',
                                 ),*/
                        ],
                ],
            'async'   => false,
            'ip_pool' => 'Main Pool',
            //'send_at' => date('Y-m-d H:i:s'),
        ];

        //template ?
        if (!empty($template)) {
            $defaultOpts['template_name']    = $template;
            $defaultOpts['template_content'] = [

            ];
        }

        //Add recipients
        if (!empty($this->to)) {
            foreach ($this->to as $to) {
                $defaultOpts['message']['to'][] = [
                    'email' => $to[0],
                    'name'  => $to[1],
                    'type'  => 'to',
                ];
            }
        }

        //reply to
        if (!empty($this->replyTo)) {
            $defaultOpts['message']['headers']['Reply-To'] = $this->replyTo[0];
        }

        $payload = array_merge_recursive_distinct($defaultOpts, $opts);

        $this->correctEncodingRecursive($payload);

        return $payload;
    }

    private function correctEncodingRecursive(&$array){

        if(!empty($array)){
            foreach($array as $key=>$val){
                if(is_array($val)){
                    $array[$key] = $this->correctEncodingRecursive($val);
                } else {
                    $array[$key] = $this->correctEncoding($val);
                }
            }
        }

        return $array;
    }

    private function correctEncoding($str){
        if (!preg_match('!!u', $str)){
            $str = utf8_encode($str);
        }
        return $str;
    }
}
