<?php

namespace WonderWp\Framework;

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
    $index     = (!empty($backtrace[1]) && $backtrace[1]['function'] == 'trace_modal') ? 1 : 0; // If the first ancestor is trace_modal(), ignore and use the second ancestor
    echo '<div style="font-style: italic;">' . $backtrace[$index]['file'] . ':<span style="color: red;">' . $backtrace[$index]['line'] . '</span></div>'; // Prints the ancestor's filepath and line number before the trace

    $args = func_get_args();
    foreach ($args as $obj) {
        if (is_null($obj)) {
            echo '<code>null</code>';
        } else {
            highlight_string(print_r($obj, true));
        }
    }
}

function array_merge_recursive_distinct(array &$array1, array &$array2)
{
    $merged = $array1;

    foreach ($array2 as $key => &$value) {
        if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
            $merged [$key] = array_merge_recursive_distinct($merged [$key], $value);
        } else {
            $merged [$key] = $value;
        }
    }

    return $merged;
}

function redirect($url)
{
    if (!headers_sent()) {
        wp_redirect($url);
    } else {
        echo '<script>window.location.href="' . $url . '"</script>';
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

function implode_recursive($glue, $array)
{
    $ret = '';

    foreach ($array as $item) {
        if (is_array($item)) {
            $ret .= implode_recursive($glue, $item) . $glue;
        } else {
            $ret .= $item . $glue;
        }
    }

    $ret = substr($ret, 0, 0 - strlen($glue));

    return $ret;
}

function get_plugin_file($pluginRoot, $filePath)
{
    $frags            = explode(DIRECTORY_SEPARATOR, trim($pluginRoot, DIRECTORY_SEPARATOR));
    $pluginFolderName = end($frags);
    $fileDest         = get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . $pluginFolderName . $filePath;
    if (!file_exists($fileDest)) {
        $fileDest = $pluginRoot . $filePath;
    }

    return $fileDest;
}

function include_plugin_file($pluginRoot, $filePath)
{
    $pluginFile = get_plugin_file($pluginRoot, $filePath);
    include($pluginFile);
}

/**
 * Test if a request has been made via ajax or not
 * Boolean, returns true or false
 * @name isAjax
 * @return boolean
 */
function isAjax()
{
    return ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_GET['forceajax']));
}
