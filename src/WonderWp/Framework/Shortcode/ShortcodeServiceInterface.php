<?php

namespace WonderWp\Framework\Shortcode;

interface ShortcodeServiceInterface
{
    /**
     * Typically where you'll have all your add_shortcode calls
     * @return mixed
     */
    public function registerShortcodes();
}
