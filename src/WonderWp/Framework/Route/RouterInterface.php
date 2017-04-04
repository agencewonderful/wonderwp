<?php

namespace WonderWp\Framework\Route;

interface RouterInterface
{
    public function registerRules();

    public function flushRules();
}
