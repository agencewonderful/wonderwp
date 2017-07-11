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
     * @codeCoverageIgnore
     */
    public function get($key, $default = null)
    {
        return !empty($default) ? $default : false;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function set($key, $val, $duration = 0)
    {
        return true;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function delete($key)
    {
        return true;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function clear()
    {
        return true;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getMultiple($keys, $default = null)
    {
        return [];
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setMultiple($values, $ttl = null)
    {
        return true;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function deleteMultiple($keys)
    {
        return true;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function has($key)
    {
        return false;
    }

}
