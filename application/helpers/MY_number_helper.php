<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Converts a 0 or 1 into true or false
 *
 * @param $number is expected to be 0 or 1. Anything else will return the same thing.
 * @return string: "true" or "false"
 */
if (!function_exists('boolean_format'))
{

	function boolean_format($number)
	{
		$CI = & get_instance();

		switch ($number)
		{
			case 0:
				$output = "false";
				break;

			case 1:
				$output = "true";
				break;

			default:
				$output = $number;
				break;
		}

		return $output;
	}

}
