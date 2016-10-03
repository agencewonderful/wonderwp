<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 03/10/2016
 * Time: 17:52
 */

namespace WonderWp\Medias;

class Medias {

    public static function mediaAtSize($mediaUrl,$size, $icon=false, $attr=''){
        global $wpdb;
        //Url to ID
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $mediaUrl ));
        return wp_get_attachment_image($attachment[0], $size, $icon, $attr);
    }

}