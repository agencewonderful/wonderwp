<?php

namespace WonderWp\Framework\Media;

use WonderWp\Framework\API\Result;
use WonderWp\Framework\DependencyInjection\Container;

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

    /**
     * @param int|\WP_Post $postId
     *
     * @return string
     */
    public static function getFeaturedImage($postId = null, $short = true)
    {
        global $post;
        if (empty($postId) && $post) {
            $postId = $post;
        }
        $imgUrl = '';
        if (!empty($post->post_featured_image)) {
            $imgUrl = $post->post_featured_image;
            if($short){
                $imgUrl = str_replace(get_bloginfo('url'), '', $imgUrl);
            }
        }
        else {
            if (!empty($postId)) {
                $imgUrl = wp_get_attachment_url(get_post_thumbnail_id($postId));
                if($short){
                    $imgUrl = str_replace(get_bloginfo('url'), '', $imgUrl);
                }
                if ($imgUrl === false) {
                    $imgUrl = '';
                }
            }
        }

        return $imgUrl;
    }

    /**
     * @param array  $file     the array from $_FILES for a specific file
     * @param string $dest     new upload folder within the upload base dir
     * @param null   $fileName you can specify a new name for the file
     *
     * @return Result
     */
    public static function uploadTo(array $file = null, $dest = '', $fileName = null)
    {
        if (empty($file)) {
            return new Result(403, ['msg' => 'file.is.empty']);
        }
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        $upload_overrides = ['test_form' => false];

        if (!empty($fileName)) {
            $file['name'] = $fileName;
        }

        $container                        = Container::getInstance();
        $container['upload_dir_override'] = $dest;
        add_filter('upload_dir', [self::class, 'wpse183245UploadDir']);
        $movefile = wp_handle_upload($file, $upload_overrides);
        remove_filter('upload_dir', [self::class, 'wpse183245UploadDir']);
        unset($container['upload_dir_override']);

        if ($movefile && !isset($movefile['error'])) {
            return new Result(200, ['moveFile' => $movefile]);
        } else {
            /**
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            return new Result(403, ['error' => $movefile['error']]);
        }
    }

    /**
     * Filter to change upload dirs on the fly
     *
     * @param array $dirs
     *
     * @return array
     */
    public static function wpse183245UploadDir(array $dirs)
    {
        $container = Container::getInstance();
        if ($container->offsetExists('upload_dir_override')) {
            $dirs['subdir'] = $container['upload_dir_override'];
            $dirs['path']   = $dirs['basedir'] . $container['upload_dir_override'];
            $dirs['url']    = $dirs['baseurl'] . $container['upload_dir_override'];
        }

        return $dirs;
    }
}
