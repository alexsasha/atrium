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




//TODO: separated query helper

$GLOBALS["CI"] =& get_instance();

function get_posts($args = array())
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('query');
	return $CI->query->get_posts($args);
}

function get_permalink($post_id)
{
	return site_url("post/" . $post_id);;
}

function get_date($post_id, $date_format = NULL)
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('query');
	return $CI->query->get_post_date($post_id, $date_format);
}

function get_user_role($user_id)
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('user');
	$user = $CI->user->get_user($user_id);

	if($user)
		return $CI->user->user_role($user->user_status);
}

function count_user_posts($user_id)
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('query');
	return $CI->query->get_user_posts_number($user_id);
}