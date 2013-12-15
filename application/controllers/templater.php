<?php

class Templater extends CI_Controller {
		
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper('template');
		$this->load->model('query');
	}


	public function _pagination($pagi_config)
	{
		$this->load->library('pagination');

		$this->pagination->initialize($pagi_config);

		return $this->pagination->create_links();
	}
	
	public function home()
	{
		$data['title'] = 'Главная';
		$this->template->get("header", $data);
	}

	public function index($page = NULL)
	{
		$this->load->helper('url');


		if($page === NULL) 
			$page = 1;

		$posts_per_page = 10; // добавить в конфиг
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_posts_number();


		$data['pagi'] = $this->_pagination(
			array(
				'uri_segment' => 1,
				'base_url' => site_url(),
				'total_rows' => $posts_count,
				'per_page' => $posts_per_page,
				'use_page_numbers' => TRUE
				)
			);

		$data['page'] = $page;
		$data['offset'] = $offset;
		if($this->template->get('home', $data) === FALSE)
			$this->template->get('index', $data);
	}

	public function post($id)
	{
		$post = $this->query->get_post($id);
		
		if($post) 
			$data['post'] = $post;

		if($this->template->get('post', $data) === FALSE)
			$this->template->get('index', $data);

	}

}