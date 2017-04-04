<?php

namespace WonderWp\Framework\Media;

class Medias
{

    /**
     * @param $mediaUrl , The url must be the one used by WordPress in the guid column
     *
     * @return int
     */
    public static function getIdFromUrl($mediaUrl)
    {
        /** @var $wpdb \wpdb */
        global $wpdb;
        //Url to ID
        $attachmentCol = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $mediaUrl));
        $imgId         = !empty($attachmentCol[0]) ? $attachmentCol[0] : 0;

        return $imgId;
    }

    /**
     * wp_get_attachment_image wrapper with an image url instead of an id
     *
     * @param string $mediaUrl
     * @param string||array $size
     * @param bool   $icon
     * @param string $attr
     *
     * @return string
     */
    public static function mediaAtSize($mediaUrl, $size, $icon = false, $attr = '')
    {
        $imgId = self::getIdFromUrl($mediaUrl);

        return wp_get_attachment_image($imgId, $size, $icon, $attr);
    }

}
