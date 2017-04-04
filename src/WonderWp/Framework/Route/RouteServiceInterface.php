<?php

namespace WonderWp\Framework\Route;

interface RouteServiceInterface
{
    /**
     * Compute your array of rules then return them
     * @return Route[]
     */
    public function getRoutes();
}
