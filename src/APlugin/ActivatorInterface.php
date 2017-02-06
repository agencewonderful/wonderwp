<?php

namespace WonderWp\APlugin;

interface ActivatorInterface{

    /**
     * Code ran upon plugin activation
     * @return $this
     */
    public function activate();

}
