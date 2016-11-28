<?php

namespace WonderWp;
use Doctrine\Common\Util\Debug;

/**
 * Trace an object
 * Easier to read than a normal print_r
 *
 * Example:
 * <code>
 * $mydata = array('field1'=>'val1','field2'=>'val2');
 * trace($mydata);
 * </code>
 *
 * @name trace
 */
function trace()
{
    $backtrace = debug_backtrace(); // Get the backtrace of this function : who called it (w/ ancestors)
    $index = ($backtrace[1]['function'] == 'trace_modal') ? 1 : 0; // If the first ancestor is trace_modal(), ignore and use the second ancestor
    echo '<div style="font-style: italic;">' . $backtrace[$index]['file'] . ':<span style="color: red;">' . $backtrace[$index]['line'] . '</span></div>'; // Prints the ancestor's filepath and line number before the trace

    $args = func_get_args();
    foreach ($args as $obj) {
        if (is_null($obj)) {
            var_dump($obj);
        } else {
            highlight_string(print_r($obj, true));
        }
    }
}

function dump($toDump,$maxDepth = 5)
{
    $backtrace = debug_backtrace(); // Get the backtrace of this function : who called it (w/ ancestors)
    echo '<div style="font-style: italic;">' . $backtrace[0]['file'] . ':<span style="color: red;">' . $backtrace[0]['line'] . '</span></div>'; // Prints the ancestor's filepath and line number before the trace
    highlight_string(Debug::dump($toDump, $maxDepth, true, false));
}

function array_merge_recursive_distinct ( array &$array1, array &$array2 )
{
    $merged = $array1;

    foreach ( $array2 as $key => &$value )
    {
        if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
        {
            $merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
        }
        else
        {
            $merged [$key] = $value;
        }
    }

    return $merged;
}

function redirect($url){
    if(!headers_sent()){
        wp_redirect($url);
    } else {
        echo'<script>window.location.href="'.$url.'"</script>';
    }
}

function paramsToHtml($params)
{
    $paramsHtml = '';
    if (!empty($params)) {
        foreach ($params as $key => $val) {
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            $paramsHtml .= ' ' . $key . ' = "' . $val . '"';
        }
    }
    return $paramsHtml;
}

function implode_recursive($glue,$array) {
    $ret = '';

    foreach ($array as $item) {
        if (is_array($item)) {
            $ret .= implode_recursive($glue,$item) . $glue;
        } else {
            $ret .= $item . $glue;
        }
    }

    $ret = substr($ret, 0, 0-strlen($glue));

    return $ret;
}