<?php 

class Template {
	public $controller_path = 'template';
	protected $CI;


	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function get($template, $data = NULL)
	{	
		if (!$this->template_exists($template)) 
			return FALSE;

		$view = $this->controller_path . '/' . $template;
		$this->CI->load->view($view, $data);
	}


	public function template_exists($template) 
	{
		$path = FCPATH . APPPATH . 'views/' . $this->controller_path  . "/$template.php";

		return file_exists($path);
	}
}
