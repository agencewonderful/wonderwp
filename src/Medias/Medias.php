<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 03/10/2016
 * Time: 17:52
 */

namespace WonderWp\Medias;

class Medias {

    public static function getIdFromUrl($mediaUrl){
        global $wpdb;
        //Url to ID
        $attachmentCol = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $mediaUrl ));
        $imgId = !empty($attachmentCol[0]) ? $attachmentCol[0] : 0;
        return $imgId;
    }

    public static function mediaAtSize($mediaUrl,$size, $icon=false, $attr=''){
        $imgId = self::getIdFromUrl($mediaUrl);
        return wp_get_attachment_image($imgId, $size, $icon, $attr);
    }

}
