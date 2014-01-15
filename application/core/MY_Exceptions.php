<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {

	public function __construct()
    {
        parent::__construct();
    }

	public function show_404_page()
	{
		$CI =& get_instance();
		$CI->load->library('template');

		if($CI->template->get('404') === FALSE)
			show_404();
	}

}