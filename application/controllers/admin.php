<?php
class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('user', 'query'));
		$this->load->helper('url');

		$this->output->enable_profiler(TRUE);

		if( !$this->user->is_admin() 
		 	&& uri_string() != "admin/login")
		 	redirect_login();
	}

	public function index()
	{
		$user_id = $this->session->userdata('ID');

		$data['title'] = 'Административная панель';
		$data['user'] = $this->user->get_user($user_id);
		
		if(	! $this->user->is_user_logged_in())
		{
			redirect_login();
		}

		$this->load->view('admin/header', $data);
		$this->load->view('admin/panel');
		$this->load->view('admin/footer');
	}

	public function login()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$login = $this->input->post('login', TRUE);
		$pass = $this->input->post('pass', TRUE);
		$login_rules = array(
			array(
				'field'   => 'login', 
				'label'   => 'Логин', 
				'rules'   => 'required'
			),
			array(
				'field'   => 'password', 
				'label'   => 'Пароль', 
				'rules'   => "callback__password_check[$login/SEP/$pass]"
			),
		);

		$this->form_validation->set_rules($login_rules);

		$data['title'] = 'Авторизация';

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/header', $data);
			$this->load->view('admin/login');
			$this->load->view('admin/footer');
		}
		else
		{
			$login = $this->input->post('login', TRUE);
			$pass = $this->input->post('pass', TRUE);
			$this->user->authenticate($login, $pass);
			redirect_admin();
		}
	}

	public function logout() 
	{
		$data['title'] = 'Вы вышли';
		$this->user->de_authenticate();
		$this->load->view('admin/header', $data);
		$this->load->view('admin/logout');
		$this->load->view('admin/footer');
	}

	public function register()
	{
		$register_rules = array(
			array(
				'field'   => 'login', 
				'label'   => 'Логин', 
				'rules'   => 'required|alpha_dash|min_length[6]|max_length[20]'
			),
			array(
				'field'   => 'email', 
				'label'   => 'E-mail', 
				'rules'   => 'required|valid_email|is_unique[users.user_email]'
			),
			array(
				'field'   => 'pass', 
				'label'   => 'Пароль', 
				'rules'   => 'required|alpha_dash|matches[pass2]|min_length[6]'
			),
			array(
				'field'   => 'pass2', 
				'label'   => 'Подтвердите пароль', 
				'rules'   => 'required|alpha_dash|min_length[6]'
			)
		);

		$login = $this->input->post('login', TRUE);
		$email = $this->input->post('email', TRUE);
		$pass = $this->input->post('pass', TRUE);
		$pass2 = $this->input->post('pass2', TRUE);

		$data['title'] = "Регистрация";

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules($register_rules);

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/header', $data);
			$this->load->view('admin/register');
			$this->load->view('admin/footer');
		}
		else
		{
			$this->load->library('encrypt');
			$encoded_pass = $this->encrypt->encode($pass);

			$this->user->update_user(
				array(
					'user_login' => $login,
					'user_pass' => $encoded_pass,
					'user_email' => $email
				)
			);

			$this->load->view('admin/header', $data);
			$this->load->view('admin/register');
			$this->load->view('admin/footer');
		}
	}

	public function create()
	{
		$valid_rules = array(
			array(
				'field'   => 'title', 
				'label'   => 'Заголовок', 
				'rules'   => 'required|is_unique[posts.post_title]'
			),
			array(
				'field'   => 'content', 
				'label'   => 'Содержание', 
				'rules'   => 'required'
			)
		);
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules($valid_rules);

		$data['title'] = "Создать новую запись";

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/header', $data);
			$this->load->view('admin/create');
			$this->load->view('admin/footer');
		}
		else
		{
			$args['post_title'] = $this->input->post('title', TRUE);
			$args['post_content'] = $this->input->post('content', TRUE);
			$this->query->update_post($args);

			$data['title'] = "Запись создана";
			$data['action'] = "создана";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
		
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
	}

	public function update($id)
	{
		if($id)
		{
			$valid_rules = array(
				array(
					'field'   => 'title', 
					'label'   => 'Заголовок', 
					'rules'   => 'required'
				),
				array(
					'field'   => 'content', 
					'label'   => 'Содержание', 
					'rules'   => 'required'
				)
			);
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($valid_rules);
			
			$post = $this->query->get_post($id);
			$data = (array) $post;
			$data['title'] = 'Редактировать запись  "' . $data['post_title'] . '"';
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('admin/header', $data);
				$this->load->view('admin/update');
				$this->load->view('admin/footer');
			}
			else
			{
				$args['ID'] = $id;
				$args['post_title'] = $this->input->post('title', TRUE);
				$args['post_content'] = $this->input->post('content', TRUE);
				$this->query->update_post($args);

				$data['title'] = "Запись обновлена";
				$data['action'] = "обновлена";
				$data['reffer'] = $this->input->server('HTTP_REFERER');

				$this->load->view('admin/header', $data);
				$this->load->view('admin/post_success');
				$this->load->view('admin/footer');
			}
		}
	}

	public function delete($id) 
	{
		if(!$id)
			return FALSE;
		
		$this->load->helper('url');

		if($this->query->change_post_status($id, 'trash') !== FALSE) 
		{
			$data['title'] = "Запись удалена";
			$data['action'] = "удалена";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
		else 
		{
			redirect('/admin/posts', 'location', 301);
		}
	}

	public function delete_post($id) 
	{
		if(!$id)
			return FALSE;
		
		$this->load->helper('url');

		if($this->query->delete_post($id, 'trash') !== FALSE) 
		{
			$data['title'] = "Запись удалена";
			$data['action'] = "удалена";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
		else 
		{
			redirect('/admin/posts', 'location', 301);
		}
	}

	public function untrash($id) 
	{
		if(!$id)
			return FALSE;
		
		$this->load->helper('url');

		if($this->query->change_post_status($id, 'publish') !== FALSE) 
		{
			$data['title'] = "Запись востановлена";
			$data['action'] = "востановлена";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
		else 
		{
			redirect('/admin/posts_trash', 'location', 301);
		}
	}
	

	public function posts($page = NULL) 
	{
		$this->load->library('pagination');
		$this->load->helper('url');


		if($page === NULL) 
			$page = 1;

		$posts_per_page = 10;
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_posts_number();
		$pagi_config['base_url'] = site_url("admin/posts");
		$pagi_config['total_rows'] = $posts_count;
		$pagi_config['per_page'] = $posts_per_page;
		$pagi_config['use_page_numbers'] = TRUE;

		$this->pagination->initialize($pagi_config);
		$data['title'] = 'Все записи';
		$data['pagi'] = $this->pagination->create_links();
 		$data['posts'] = $this->query->get_posts(
			array(
				'numberposts' => $posts_per_page,
		        'order' => 'DESC',
		        'orderby' => 'post_date',
		        'offset' => $offset
		    )
		);

		$this->load->view('admin/header', $data);
		$this->load->view('admin/posts');
		$this->load->view('admin/footer'); 
	}

	public function posts_trash($page = NULL) 
	{
		$this->load->library('pagination');
		$this->load->helper('url');


		if($page === NULL) 
			$page = 1;

		$posts_per_page = 10;
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_posts_number(array('post_status' => 'trash'));
		
		$pagi_config['base_url'] = site_url("admin/posts_trash");
		$pagi_config['total_rows'] = $posts_count;
		$pagi_config['per_page'] = $posts_per_page;
		$pagi_config['use_page_numbers'] = TRUE;
		
			$this->pagination->initialize($pagi_config);
			$data['pagi'] = $this->pagination->create_links();
		
		$data['title'] = 'Записи в корзине';
		$data['is_trash'] = TRUE;
 		$data['posts'] = $this->query->get_posts(
			array(
				'numberposts' => $posts_per_page,
		        'order' => 'DESC',
		        'orderby' => 'post_date',
		        'offset' => $offset,
		        'post_status' => 'trash'
		    )
		);

		$this->load->view('admin/header', $data);
		$this->load->view('admin/posts');
		$this->load->view('admin/footer'); 
	}


	public function settings()
	{
		$settings_rules = array(
			array(
				'field'   => 'sitename', 
				'label'   => 'Название сайта', 
				'rules'   => ''
			),
			array(
				'field'   => 'sitedesc', 
				'label'   => 'Краткое описание', 
				'rules'   => ''
			),
			array(
				'field'   => 'date_format', 
				'label'   => 'Формат даты', 
				'rules'   => ''
			)
		);

		$sitename = $this->input->post('sitename', TRUE);
		$sitedesc = $this->input->post('sitedesc', TRUE);
		$timezones = $this->input->post('timezones', TRUE);
		$date_format = $this->input->post('date_format', TRUE);

		$data['title'] = "Настройки";
		$data['sitename'] = $this->config->get('sitename');
		$data['sitedesc'] = $this->config->get('sitedesc');
		$data['timezones'] = $this->config->get('timezones');
		$data['date_format'] = $this->config->get('date_format');

		$this->load->helper(array('form', 'url', 'date'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules($settings_rules);

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/header', $data);
			$this->load->view('admin/settings');
			$this->load->view('admin/footer');
		}
		else
		{
			$this->config->set('sitename', $sitename);
			$this->config->set('sitedesc', $sitedesc);
			$this->config->set('timezones', $timezones);
			$this->config->set('date_format', $date_format);

			$this->load->view('admin/header', $data);
			$this->load->view('admin/settings');
			$this->load->view('admin/footer');
		}
	}

	public function users()
	{
		$this->load->helper('date');
		$this->load->helper('template');

		$data['title'] = 'Пользователи';
		$data['users'] = $this->user->get_users();
		$data['timezones'] = $this->config->get('timezones');

		$this->load->view('admin/header', $data);
		$this->load->view('admin/users');
		$this->load->view('admin/footer');
	}

	public function user_delete($id)
	{
		if(!$id)
			return FALSE;
		
		$this->load->helper('url');

		if($this->user->delete_user($id) !== FALSE) 
		{
			$data['title'] = "Пользователь удален";
			$data['action'] = "удалена";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
		else 
		{
			redirect('/admin/users', 'location', 301);
		}

	}

	public function _password_check($arg, $auth)
	{
		
		$auth = explode("/SEP/", $auth);
		if($this->user->check_login($auth[0], $auth[1])) 
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('password_check', 'Логин или пароль неверен');
			return FALSE;
		}
	}

	public function category()
	{
		$term_rules = array(
			array(
				'field'   => 'name', 
				'label'   => 'Название', 
				'rules'   => 'required|is_unique[terms.name]'
			),
			array(
				'field'   => 'slug', 
				'label'   => 'Ярлык', 
				'rules'   => 'alpha_dash'
			),
			array(
				'field'   => 'desc', 
				'label'   => 'Содержание', 
				'rules'   => ''
			)
		);

		$this->load->helper(array('form', 'url'));
		$this->load->model('query_tax');
		$this->load->library('form_validation');
		$this->form_validation->set_rules($term_rules);

		$taxonomy = 'category'; 

		$data['title'] = 'Категории';
		$data['terms'] = $this->query_tax->get_terms($taxonomy);

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/header', $data);
			$this->load->view('admin/category');
			$this->load->view('admin/footer');
		}
		else
		{	
			$term_name = $this->input->post('name', TRUE);
			$slug = $this->input->post('slug', TRUE);
			$desc = $this->input->post('desc', TRUE);

			$this->query_tax->insert_term($term_name, $taxonomy, array(
				'slug' => $slug,
				'description' => $desc
				)
			);

			$data['title'] = "Категория создана";
			$data['action'] = "создана";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
		
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
	}

	public function term_delete($id)
	{
		if(!$id)
			return FALSE;
		
		$this->load->helper('url');
		$this->load->model('query_tax');

		if($this->query_tax->delete_term($id) !== FALSE) 
		{
			$data['title'] = "Категория удалена";
			$data['action'] = "удалена";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
		else 
		{
			redirect('/admin/category', 'location', 301);
		}
	}

	public function term_update($id)
	{
		$term_rules = array(
			array(
				'field'   => 'name', 
				'label'   => 'Название', 
				'rules'   => 'required'
			),
			array(
				'field'   => 'slug', 
				'label'   => 'Ярлык', 
				'rules'   => 'alpha_dash'
			),
			array(
				'field'   => 'desc', 
				'label'   => 'Содержание', 
				'rules'   => ''
			)
		);

		if( ! $id)
			return FALSE;

		$this->load->helper('url');
		$this->load->model('query_tax');
		$this->load->library('form_validation');


		$taxonmy = 'category';
		$term = $this->query_tax->term_exists($id, $taxonmy, 'term_id');
		if( ! $term)
			return FALSE;
		
		if($term)
			$term = $term[0];

		$data['term'] = $term;

		$args['name'] = $this->input->post('name', TRUE);
		$args['slug'] = $this->input->post('slug', TRUE);
		$args['description'] = $this->input->post('desc', TRUE);

		$this->form_validation->set_rules($term_rules);
		if($this->form_validation->run() == FALSE) 
		{
			$this->load->view('admin/header', $data);
			$this->load->view('admin/term_update');
			$this->load->view('admin/footer');
		}
		else 
		{
			$data['title'] = "Категория обновлена";
			$data['action'] = "обновлена";
			$data['reffer'] = $this->input->server('HTTP_REFERER');

			$this->query_tax->update_term($id, $taxonmy, $args);
			
			$this->load->view('admin/header', $data);
			$this->load->view('admin/post_success');
			$this->load->view('admin/footer');
		}
	}
}