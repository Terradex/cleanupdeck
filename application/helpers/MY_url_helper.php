<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Site URL
 *
 * Create a local URL based on your basepath. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('base_domain'))
{

    function base_domain()
    {
        $CI = & get_instance();
        $base_url = $CI->config->slash_item('base_url');
        preg_match('@^(?:http://)?([^/]+)@i', $base_url, $matches);
        $host = $matches[1];
        preg_match('/[^.]+\.[^.]+$/', $host, $matches);

        return $matches[0];
    }

}
