<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 19/04/2017
 * Time: 16:31
 */

namespace WonderWp\Framework\Cache;

class DisabledCache implements CacheInterface
{
    /**
     * @inheritDoc
     */
    public function get($key)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $val, $duration = 0)
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        return $this;
    }

}
