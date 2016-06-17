<?php

namespace WonderWp;

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