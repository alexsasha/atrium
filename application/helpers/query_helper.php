<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

function is_user_logged_in()
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('user');

	return $CI->user->is_user_logged_in();
}

function get_user($user_id)
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('user');

	return $CI->user->get_user($user_id);
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

function get_the_terms($post_id)
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('query_tax');

	return $CI->query_tax->get_post_terms($post_id);
}

function get_pagination($config = array())
{
	$CI = $GLOBALS["CI"];
	$CI->load->model('templater');

	return $CI->templater->_pagination($config);
}

function get_siteinfo($key)
{
	$CI = $GLOBALS["CI"];
	return $CI->config->get($key);
}