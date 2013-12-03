<?php
class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('query', '', TRUE);
	}

	public function index()
	{
		$data['info'] = "Admin Info";
		$data['title'] = 'News archive';
		$data['query'] = $this->query->get_posts(
			array(
		        'numberposts' => -1,
		        'orderby' => 'post_name',
		        'order' => 'ASC'
		    )
		);
		$this->load->view('admin/admin_panel', $data);
	}

	public function view($slug)
	{

	}

	public function create($id = NULL)
	{
		//если $id не определен, выводим форму
		if($id === NULL) 
		{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Название', 'required|is_unique[posts.post_title]');
			$this->form_validation->set_rules('content', 'Content', 'required');

			$data['title'] = "Создать новую запись";

			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('admin/admin_create', $data);

			}
			else
			{
				$args['post_title'] = $this->input->post('title', TRUE);
				$args['post_content'] = $this->input->post('content', TRUE);
				$this->query->update_post($args);
				$this->load->view('admin/admin_post_succes');
			}
		}
	}

	public function posts($page = NULL) {
		if($page === NULL) 
			$page = 1;

		$posts_per_page = 10;
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_posts_number();

		$data['posts'] = $this->query->get_posts(
			array(
				'numberposts' => $posts_per_page,
		        'offset' => 'post_dat',
		        'order' => 'DESC',
		        'offset' => $offset
		    )
		);

		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->view('admin/posts', $data);

		
		$pagi_config['base_url'] = site_url("admin/posts");
		$pagi_config['total_rows'] = $posts_count;
		$pagi_config['per_page'] = $posts_per_page;
		$pagi_config['use_page_numbers'] = TRUE; 

		$this->pagination->initialize($pagi_config); 

		echo $this->pagination->create_links();
	}

	public function test1() {
		echo "kaka"; 	
	}
}