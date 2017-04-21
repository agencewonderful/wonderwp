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
    public function get($key)
    {
        return get_transient($key);
    }

    /** @inheritdoc */
    public function set($key, $val, $duration = 0)
    {
        set_transient($key, $val, $duration);

        return $this;
    }

    /** @inheritdoc */
    public function delete($key)
    {
        delete_transient($key);

        return $this;
    }

}
