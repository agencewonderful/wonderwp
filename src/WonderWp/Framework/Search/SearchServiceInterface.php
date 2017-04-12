<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:08
 */

namespace WonderWp\Framework\Search;

interface SearchServiceInterface
{

    public function getResultSet($query);

}
