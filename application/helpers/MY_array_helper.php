<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function pars_args($args, $defaults) 
{
	if (is_array($defaults))
		return array_merge($defaults, $args);
}