<?php

class Templater extends CI_Controller {

	var $posts_per_page;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('query');
		$this->load->library('template');
		$this->load->helper(array('template', 'query'));

		$this->posts_per_page = $this->config->get('posts_per_page');
	}

	public function index($page = NULL)
	{
		$this->load->helper('url');

		if($page === NULL) 
			$page = 1;

		$posts_per_page = $this->posts_per_page;
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_posts_number(array('post_status' => 'publish'));

		$data['posts'] = $this->query->get_posts(
			array(
		        'order' => 'DESC',
		        'orderby' => 'post_date',
				'offset' => $offset
			)
		);
		$data['page'] = $page;
		$data['pagi'] = $this->_pagination(array('total_rows' => $posts_count));

		if($this->template->get('home', $data) === FALSE)
			$this->template->get('index', $data);
	}

	public function post($id = NULL)
	{
		$post = $this->query->get_post($id);

		if($id === NULL || ! $post)
		{
			show_404_page();
		}
		else
		{
			$data['post'] = $post;

			if($this->template->get('post', $data) === FALSE)
				$this->template->get('index', $data);
		}
	}

	public function category($term_id = NULL, $page = NULL)
	{
		$this->load->helper('url');

		if($term_id === NULL) 
		{
			show_404_page();
		}
		else
		{
			if($page === NULL) 
				$page = 1;

			$posts_per_page = $this->posts_per_page;
			$offset = ($page - 1) * $posts_per_page;
			$term_ids = array($term_id);
			$posts_count = $this->query->get_posts_number(array('category' => $term_ids));
			$data['posts'] = $this->query->get_posts(
				array(
					'category' => $term_ids,
			        'order' => 'DESC',
			        'orderby' => 'post_date',
					'offset' => $offset
				)
			);
			$data['page'] = $page;
			$data['pagi'] = $this->_pagination(array(
				'total_rows' => $posts_count,
				'uri_segment' => 3,
				'base_url' => site_url('category/' . $term_id)
				)
			);

			if($this->template->get('category', $data) === FALSE)
				$this->template->get('index', $data);
		}
	}

	public function author($user_id = NULL, $page = NULL)
	{
		$this->load->helper('url');

		if($user_id === NULL) 
			$user_id = 1;
		if($page === NULL) 
			$page = 1;

		$posts_per_page = $this->posts_per_page;
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_user_posts_number($user_id);

		$data['posts'] = $this->query->get_posts(
			array(
				'post_author' => $user_id,
		        'order' => 'DESC',
		        'orderby' => 'post_date',
				'offset' => $offset
			)
		);
		$data['page'] = $page;
		$data['pagi'] = $this->_pagination(array(
			'total_rows' => $posts_count,
			'uri_segment' => 3,
			'base_url' => site_url('author/' . $user_id)
			)
		);

		if($this->template->get('author', $data) === FALSE)
			$this->template->get('index', $data);
	}

	private function _pagination($pagi_config = array())
	{
		$this->load->library('pagination');
		$this->load->helper('url');
		
		$defaults = array(
	        'uri_segment' => 1,
			'base_url' => site_url(),
			'per_page' => $this->posts_per_page,
			'use_page_numbers' => TRUE
	    );
	    $r = pars_args($pagi_config, $defaults);

		$this->pagination->initialize($r);

		return $this->pagination->create_links();
	}
}