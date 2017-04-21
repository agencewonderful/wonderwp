<?php

namespace WonderWp\Framework\Cache;

interface CacheInterface
{
    /**
     * @param string $key
     *
     * @return mixed|false
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed  $val
     * @param int $duration
     *
     * @return static
     */
    public function set($key, $val, $duration = 0);

    /**
     * @param string $key
     *
     * @return static
     */
    public function delete($key);
}
