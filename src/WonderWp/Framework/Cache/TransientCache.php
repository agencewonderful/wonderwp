<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 19/04/2017
 * Time: 16:20
 */

namespace WonderWp\Framework\Cache;

class TransientCache implements CacheInterface
{
    /** @inheritdoc */
    public function get($key, $default = null)
    {
        $val = get_transient($key);

        return !empty($val) ? $val : $default;
    }

    /** @inheritdoc */
    public function set($key, $val, $duration = 0)
    {
        return set_transient($key, $val, $duration);
    }

    /** @inheritdoc */
    public function delete($key)
    {
        return delete_transient($key);
    }

    /** @inheritdoc */
    public function clear()
    {
        $wpdb = $this->getDb();
        $res1 = $wpdb->query($wpdb->prepare('DELETE FROM `%s` WHERE `option_name` LIKE (\'_transient_%\')',$wpdb->prefix.'options'));
        $res2 = $wpdb->query($wpdb->prepare('DELETE FROM `%s` WHERE `option_name` LIKE (\'_site_transient_%\');',$wpdb->prefix.'options'));
        return (!$res1 && !$res2);
    }

    /** @inheritdoc */
    public function getMultiple($keys, $default = null)
    {
        $cached = [];
        if(!empty($keys)){
            foreach($keys as $transientName){
                $cached[$transientName] = $this->get($transientName,$default);
            }
        }
        return $cached;
    }

    /** @inheritdoc */
    public function setMultiple($values, $ttl = null)
    {
        $success = true;
        if(!empty($values)){
            foreach ($values as $key=>$val){
                $thisSuccess = $this->set($key,$val,$ttl);
                if(!$thisSuccess){ $success = $thisSuccess; }
            }
        }
        return $success;
    }

    /** @inheritdoc */
    public function deleteMultiple($keys)
    {
        $success = true;
        if(!empty($keys)){
            foreach($keys as $transientName){
                $thisSuccess = $this->delete($transientName);
                if(!$thisSuccess){ $success = $thisSuccess; }
            }
        }
        return $success;
    }

    /** @inheritdoc */
    public function has($key)
    {
        $wpdb = $this->getDb();
        $res = $wpdb->query($wpdb->prepare('SELECT * FROM `%s` WHERE `option_name` LIKE (\'%s\')',$wpdb->prefix.'options','_transient_'.$key));
        return !empty($res);
    }

    /**
     * @return \wpdb
     */
    private function getDb(){
        global $wpdb;
        return $wpdb;
    }

}
