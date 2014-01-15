<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_header()
{
	$Templ = new Template;
	$Templ->get('header');
}

function get_footer()
{
	$Templ = new Template;
	$Templ->get('footer');
}

function get_sidebar()
{
	$Templ = new Template;
	$Templ->get('sidebar');
}

function show_404_page()
{
	$Templ = new Template;
	if($Templ->get('404') === FALSE)
	{
		show_404();
	}
}