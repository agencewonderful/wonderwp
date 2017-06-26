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
    public function get($key)
    {
        return false;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function set($key, $val, $duration = 0)
    {
        return $this;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function delete($key)
    {
        return $this;
    }

}
