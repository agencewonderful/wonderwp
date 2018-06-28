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

/**
 *
 * @param array $array1
 * @param array $array2
 *
 * @return array
 */
function array_merge_recursive_distinct(array &$array1, array &$array2)
{
    $merged = $array1;

    foreach ($array2 as $key => &$value) {
        if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
            $merged[$key] = array_merge_recursive_distinct($merged [$key], $value);
        } else {
            $merged[$key] = $value;
        }
    }

    return $merged;
}

/**
 * array_diff but recursive
 * @param array $aArray1
 * @param array $aArray2
 *
 * @return array
 */
function array_diff_recursive(array $aArray1, array $aArray2) {
    $aReturn = array();

    foreach ($aArray1 as $mKey => $mValue) {
        if (array_key_exists($mKey, $aArray2)) {
            if (is_array($mValue)) {
                $aRecursiveDiff = array_diff_recursive($mValue, $aArray2[$mKey]);
                if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
            } else {
                if ($mValue != $aArray2[$mKey]) {
                    $aReturn[$mKey] = $mValue;
                }
            }
        } else {
            $aReturn[$mKey] = $mValue;
        }
    }
    return $aReturn;
}

/**
 * array_filter, but recursive
 * @param array $input
 *
 * @return array
 */
function array_filter_recursive(array $input)
{
    foreach ($input as &$value)
    {
        if (is_array($value))
        {
            $value = array_filter_recursive($value);
        }
    }

    return array_filter($input);
}

/**
 * implode, but recursive
 * @param string $glue
 * @param array  $array
 *
 * @return bool|string
 */
function implode_recursive($glue, array $array)
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

/**
 * Redirect to a url either via the back end if headers have not been sent, or via the frontend otherwise
 * @param string $url
 */
function redirect($url)
{
    if (!headers_sent()) {
        wp_redirect($url);
    } else {
        echo '<script>window.location.href="' . $url . '"</script>';
    }
}

/**
 * Takes an array of params and builds html arguments with them
 * @param array $params
 *
 * @return string
 */
function paramsToHtml(array $params)
{
    $html = '';

    foreach ($params as $key => $val) {
        if (is_array($val)) {
            $val = implode(' ', $val);
        }

        $html .= " {$key}=\"{$val}\"";
    }

    return $html;
}

/**
 * Locates and returns plugin file path,
 * either in the theme if the override version exists, or in the plugin directory otherwise
 *
 * @param string $pluginRoot
 * @param string $filePath
 *
 * @return string
 */
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

/**
 * @param string $pluginRoot
 * @param string $filePath
 */
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
