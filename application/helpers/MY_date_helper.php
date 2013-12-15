<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function gmt_to_local_date($date_format, $date, $timezone = NULL)
{
	$date = date_parse_from_format($date_format, $date);

    $timestamp = mktime($date['hour'], $date['minute'], $date['second'] , $date['month'], $date['day'], $date['year']);

    return date($date_format, gmt_to_local($timestamp, $timezone));
}