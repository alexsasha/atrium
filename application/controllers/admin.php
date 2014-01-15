<?php
class Admin extends CI_Controller {

	var $posts_per_page,
	$default_taxonomy = 'category';

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('user', 'query'));
		$this->load->helper(array('url', 'query'));

		//$this->output->enable_profiler(TRUE);

		//Если пользователь не админ перенаправляем на страницу авторизации
		if( ! $this->user->is_admin() 
		 	&& uri_string() != "admin/login")
		 	redirect_login();

		$this->posts_per_page = $this->config->get('admin_posts_per_page');
	}


	/* Действие контроллера по умолчанию */
	public function index()
	{
		$this->load->model('query_tax');

		$user_id = $this->session->userdata('ID');
		$taxonomy = $this->default_taxonomy;

		$data['title'] = 'Административная панель';
		$data['user'] = $this->user->get_user($user_id);
		$data['count_posts'] = $this->query->get_posts_number(array('post_status' => 'publish'));
		$data['count_cats'] = $this->query_tax->get_terms_number($taxonomy);
		$data['count_users'] = $this->user->get_users_number($taxonomy);
		
		$this->load_view('panel', $data);
	}


	/* Обработка действий с постами */
	public function posts($page = NULL) 
	{
		$this->load->model('query_tax');
		$this->load->library('pagination');

		if($page === NULL) 
			$page = 1;

		$taxonomy = $this->default_taxonomy;
		$posts_per_page = $this->posts_per_page;
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_posts_number(array('post_status' => 'publish'));
		
		$pagi_config['base_url'] = site_url("admin/posts");
		$pagi_config['total_rows'] = $posts_count;
		$pagi_config['per_page'] = $posts_per_page;
		$pagi_config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($pagi_config);
		
		$data['title'] = 'Все записи';
		$data['posts'] = array();
		$data['publish_posts_count'] = $posts_count;
		$data['trash_posts_count'] = $this->query->get_posts_number(array('post_status' => 'trash'));
		$data['pagi'] = $this->pagination->create_links();

 		$posts = $this->query->get_posts(
			array(
				'numberposts' => $posts_per_page,
		        'order' => 'DESC',
		        'orderby' => 'post_date',
		        'offset' => $offset
		    )
		);
		
		
		if($posts)
		{
			foreach ($posts as $post) 
			{
				$terms = $this->query_tax->get_post_terms($post->ID);
				$term_names = array();
				if($terms)
				{
					foreach ($terms as $term) {
						$term_obj = $this->query_tax->term_exists($term->term_id, $taxonomy, 'term_id');
						$term_names[] = $term_obj->name;
					}
				}
				if($term_names)
					$post->terms = implode(', ', $term_names);
				else
					$post->terms = '-';

				$data['posts'][] = $post;
			}
		}

		$this->load_view('posts', $data);
	}

	public function posts_trash($page = NULL) 
	{
		$this->load->model('query_tax');
		$this->load->library('pagination');

		if($page === NULL) 
			$page = 1;

		$taxonomy = $this->default_taxonomy;
		$posts_per_page = $this->posts_per_page;
		$offset = ($page - 1) * $posts_per_page;
		$posts_count = $this->query->get_posts_number(array('post_status' => 'trash'));
		
		$pagi_config['base_url'] = site_url("admin/posts_trash");
		$pagi_config['total_rows'] = $posts_count;
		$pagi_config['per_page'] = $posts_per_page;
		$pagi_config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($pagi_config);
		
		$data['title'] = 'Записи в корзине';
		$data['pagi'] = $this->pagination->create_links();
		$data['posts'] = array();
		$data['publish_posts_count'] = $this->query->get_posts_number(array('post_status' => 'publish'));
		$data['trash_posts_count'] = $posts_count;
		$data['is_trash'] = TRUE;
 		
 		$posts = $this->query->get_posts(
			array(
				'numberposts' => $posts_per_page,
		        'order' => 'DESC',
		        'orderby' => 'post_date',
		        'offset' => $offset,
		        'post_status' => 'trash'
		    )
		);

		if($posts)
		{
			foreach ($posts as $post) 
			{
				$terms = $this->query_tax->get_post_terms($post->ID);

				if($terms)
				{
					$term_names = array();
					foreach ($terms as $term) {
						$term_obj = $this->query_tax->term_exists($term->term_id, $taxonomy, 'term_id');
						$term_names[] = $term_obj->name;
					}
				}
				if(isset($term_names))
					$post->terms = implode(', ', $term_names);
				else
					$post->terms = '-';

				$data['posts'][] = $post;
			}
		}

		$this->load_view('posts', $data);
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->model('query_tax');
		$this->load->library('form_validation');
		
		$taxonomy = $this->default_taxonomy;
		$valid_rules = array(
			array(
				'field'   => 'title', 
				'label'   => '"Заголовок"', 
				'rules'   => 'required|is_unique[posts.post_title]'
			),
			array(
				'field'   => 'content', 
				'label'   => '"Содержание"', 
				'rules'   => 'required'
			)
		);
		$this->form_validation->set_rules($valid_rules);

		$terms = $this->query_tax->get_terms($taxonomy);

		foreach ($terms as $term) 
		{
			$terms_names[$term->term_id] = $term->name;
		}

		$data['terms'] = $terms_names;
		$data['title'] = "Создать новую запись";
		$data['head'][] = "<script type='text/javascript' src='" . base_url() . "js/tinymce/tinymce.min.js'></script>";
		$data['js'] = "
			tinymce.init({
			    selector: '#content',
			    height: 500,
			    language: 'ru'
			});
		";  

		if ($this->form_validation->run() == FALSE)
		{
			$this->load_view('create', $data);
		}
		else
		{
			$args['post_title'] = $this->input->post('title', TRUE);
			$args['post_content'] = $this->input->post('content', TRUE);
	
			$insert_id = $this->query->update_post($args);

			$terms_checked = $this->input->post('terms', TRUE);
			$this->query_tax->set_post_terms($insert_id, $terms_checked);

			$data['title'] = "Запись создана";
			$data['action'] = "Запись успешно создана.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');

			$this->load_view('success', $data);
		}
	}

	public function update($id = NULL)
	{
		$post = $this->query->get_post($id);

		if($id === NULL 
			|| ! $post)
			redirect_admin('posts');

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('query_tax');

		$valid_rules = array(
			array(
				'field'   => 'title', 
				'label'   => '"Заголовок"', 
				'rules'   => 'required'
			),
			array(
				'field'   => 'content', 
				'label'   => '"Содержание"', 
				'rules'   => 'required'
			)
		);
		$this->form_validation->set_rules($valid_rules);
		
		$taxonomy = $this->default_taxonomy;
		$data = (array) $post;

		$terms = $this->query_tax->get_terms($taxonomy);
		foreach ($terms as $term) 
		{
			$terms_names[$term->term_id] = $term->name;
		}
		$data['terms'] = $terms_names;

		$terms_checked = $this->query_tax->get_post_terms($id);
		if($terms_checked)
		{
			$data['terms_checked'] = array();
			foreach ($terms_checked as $term) 
			{
				$data['terms_checked'][] = $term->term_id;
			}
		}

		$data['title'] = 'Редактирование записи "' . $data['post_title'] . '"';
		$data['head'][] = "<script type='text/javascript' src='" . base_url() . "js/tinymce/tinymce.min.js'></script>";
		$data['js'] = "
			tinymce.init({
			    selector: '#content',
			    height: 500,
			    language: 'ru'
			});
		";  
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load_view('update', $data);
		}
		else
		{
			$args['ID'] = $id;
			$args['post_title'] = $this->input->post('title', TRUE);
			$args['post_content'] = $this->input->post('content', TRUE);
			$this->query->update_post($args);

			$terms_checked = $this->input->post('terms', TRUE);
			if( ! $terms_checked)
				$terms_checked = array();
			$this->query_tax->set_post_terms($id, $terms_checked);

			$data['title'] = "Запись обновлена";
			$data['action'] = "Запись успешно обновлена.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');

			$this->load_view('success', $data);
		}
	}

	public function delete($id = NULL) 
	{
		if($id === NULL)
			redirect_admin('posts');

		if($this->query->change_post_status($id, 'trash') !== FALSE) 
		{
			$data['title'] = "Запись удалена";
			$data['action'] = "Запись успешно перенесена в корзину.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load_view('success', $data);
		}
		else 
		{
			redirect_admin('posts');
		}
	}

	public function delete_post($id = NULL) 
	{
		if($id === NULL)
			redirect_admin('posts');

		if($this->query->delete_post($id) !== FALSE) 
		{
			$data['title'] = "Запись удалена";
			$data['action'] = "Запись успешно удалена.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load_view('success', $data);
		}
		else 
		{
			redirect_admin('posts');
		}
	}

	public function untrash($id = NULL) 
	{
		if($id === NULL)
			redirect_admin('posts_trash');
		
		if($this->query->change_post_status($id, 'publish') !== FALSE) 
		{
			$data['title'] = "Запись востановлена";
			$data['action'] = "Запись успешно востановлена.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load_view('success', $data);
		}
		else 
		{
			redirect_admin('posts_trash');
		}
	}


	/* Обработка пользовательских действий */
	public function users()
	{
		$this->load->helper(array('date', 'query'));

		$data['title'] = 'Пользователи';
		$data['title'] = 'Пользователи';
		$users = $this->user->get_users();
		if($users)
		{
			$data['users'] = $users;
			$data['users_count'] = count($users);

			
		}
		$data['timezones'] = $this->config->get('timezones');

		$this->load_view('users', $data);
	}

	public function user_delete($id = NULL)
	{
		if($id === NULL)
			redirect_admin('users');
		
		if($this->user->delete_user($id) !== FALSE) 
		{
			$data['title'] = "Пользователь удален";
			$data['action'] = "Пользователь успешно удален.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load_view('success', $data);
		}
		else 
		{
			redirect_admin('users');
		}
	}

	public function login()
	{
		if($this->user->is_user_logged_in())
			redirect_admin();

		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$login = $this->input->post('login', TRUE);
		$pass = $this->input->post('pass', TRUE);

		$login_rules = array(
			array(
				'field'   => 'login', 
				'label'   => '"Логин"', 
				'rules'   => 'required'
			),
			array(
				'field'   => 'pass', 
				'label'   => '"Пароль"', 
				'rules'   => "required|callback__password_check[$login/SEPARATOR/$pass]"
			),
		);
		$this->form_validation->set_rules($login_rules);

		if ($this->form_validation->run() == FALSE)
		{

			$data['title'] = 'Авторизация';

			$this->load_view('login', $data);
		}
		else
		{
			if($this->user->authenticate($login, $pass));
				redirect_admin();
		}
	}

	public function logout() 
	{
		$data['title'] = 'Вы вышли';

		$this->user->de_authenticate();
		$this->load_view('logout', $data);
	}

	public function register()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$login = $this->input->post('login', TRUE);
		$email = $this->input->post('email', TRUE);
		$pass = $this->input->post('pass', TRUE);
		$pass2 = $this->input->post('pass2', TRUE);

		$register_rules = array(
			array(
				'field'   => 'login', 
				'label'   => '"Логин"', 
				'rules'   => 'required|alpha_dash|min_length[6]|max_length[20]'
			),
			array(
				'field'   => 'email', 
				'label'   => '"E-mail"', 
				'rules'   => 'required|valid_email|is_unique[users.user_email]'
			),
			array(
				'field'   => 'pass', 
				'label'   => '"Пароль"', 
				'rules'   => 'required|alpha_dash|matches[pass2]|min_length[6]'
			),
			array(
				'field'   => 'pass2', 
				'label'   => '"Подтвердите пароль"', 
				'rules'   => 'required|alpha_dash|min_length[6]'
			)
		);
		$this->form_validation->set_rules($register_rules);


		if ($this->form_validation->run() == FALSE)
		{
			$data['title'] = "Регистрация";
			$this->load_view('register', $data);
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

			$data['title'] = "Пользователь зарегистрирован";
			$data['action'] = "Пользователь успешно зарегистрирован.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');

			$this->load_view('success', $data);
		}
	}


	/* Обработка страницы настроек */
	public function settings()
	{
		$this->load->helper(array('form', 'date'));
		$this->load->library('form_validation');

		$sitename = $this->input->post('sitename', TRUE);
		$sitedesc = $this->input->post('sitedesc', TRUE);
		$timezones = $this->input->post('timezones', TRUE);
		$date_format = $this->input->post('date_format', TRUE);
		$posts_per_page = $this->input->post('posts_per_page', TRUE);
		$admin_posts_per_page = $this->input->post('admin_posts_per_page', TRUE);


		$settings_rules = array(
			array(
				'field'   => 'sitename', 
				'label'   => '"Название сайта"', 
				'rules'   => ''
			),
			array(
				'field'   => 'sitedesc', 
				'label'   => '"Краткое описание"', 
				'rules'   => ''
			),
			array(
				'field'   => 'date_format', 
				'label'   => '"Формат даты"', 
				'rules'   => ''
			),
			array(
				'field'   => 'admin_posts_per_page', 
				'label'   => '"Постов на страницу в админке"', 
				'rules'   => 'numeric'
			),
			array(
				'field'   => 'posts_per_page', 
				'label'   => '"Постов на страницу на сайте"', 
				'rules'   => 'numeric'
			)
		);
		$this->form_validation->set_rules($settings_rules);

		$data['title'] = "Настройки";
		$data['sitename'] = $this->config->get('sitename');
		$data['sitedesc'] = $this->config->get('sitedesc');
		$data['timezones'] = $this->config->get('timezones');
		$data['date_format'] = $this->config->get('date_format');
		$data['posts_per_page'] = $this->config->get('posts_per_page');
		$data['admin_posts_per_page'] = $this->config->get('admin_posts_per_page');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load_view('settings', $data);
		}
		else
		{
			$this->config->set('sitename', $sitename);
			$this->config->set('sitedesc', $sitedesc);
			$this->config->set('timezones', $timezones);
			$this->config->set('date_format', $date_format);
			$this->config->set('posts_per_page', $posts_per_page);
			$this->config->set('admin_posts_per_page', $admin_posts_per_page);

			redirect_admin('settings');
		}
	}


	/* Обработка действий с таксономиями */
	public function category()
	{
		$this->load->helper('form');
		$this->load->model('query_tax');
		$this->load->library('form_validation');

		$taxonomy = $this->default_taxonomy;
		$term_rules = array(
			array(
				'field'   => 'name', 
				'label'   => '"Название"', 
				'rules'   => 'required|is_unique[terms.name]'
			),
			array(
				'field'   => 'slug', 
				'label'   => '"Ярлык"', 
				'rules'   => 'alpha_dash'
			),
			array(
				'field'   => 'desc', 
				'label'   => '"Описание"', 
				'rules'   => ''
			)
		);
		$this->form_validation->set_rules($term_rules);		

		$terms = $this->query_tax->get_terms($taxonomy);

		$terms_names = array('0' => 'Нет');
		foreach ($terms as $term) 
		{
			$terms_names[$term->term_id] = $term->name;
		}

		$data['title'] = 'Категории';
		$data['terms'] = $terms;
		$data['terms_names'] = $terms_names;
		$data['terms_count'] = count($terms);

		if ($this->form_validation->run() == FALSE)
		{
			$this->load_view('category', $data);
		}
		else
		{	
			$term_name = $this->input->post('name', TRUE);
			$slug = $this->input->post('slug', TRUE);
			$desc = $this->input->post('desc', TRUE);
			$parent = $this->input->post('term_parent', TRUE);

			$this->query_tax->insert_term($term_name, $taxonomy, array(
				'slug' => $slug,
				'description' => $desc,
				'parent' => $parent
				)
			);

			$data['title'] = "Категория создана";
			$data['action'] = "Категория успешно создана.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');

			$this->load_view('success', $data);
		}
	}

	public function term_delete($id = NULL)
	{
		if($id === NULL)
			redirect_admin('category');
		
		$this->load->model('query_tax');

		if($this->query_tax->delete_term($id) !== FALSE) 
		{
			$data['title'] = "Категория удалена";
			$data['action'] = "Категория успешно удалена.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');
			
			$this->load_view('success', $data);
		}
		else 
		{
			redirect_admin('category');
		}
	}

	public function term_update($id = NULL)
	{
		$this->load->model('query_tax');
		$this->load->library('form_validation');
		$this->load->helper('form');

		$taxonomy = $this->default_taxonomy;
		$term = $this->query_tax->term_exists($id, $taxonomy, 'term_id');

		if($id === NULL || ! $term)
			redirect_admin('category');

		$term_rules = array(
			array(
				'field'   => 'name', 
				'label'   => '"Название"', 
				'rules'   => 'required'
			),
			array(
				'field'   => 'slug', 
				'label'   => '"Ярлык"', 
				'rules'   => 'alpha_dash'
			),
			array(
				'field'   => 'desc', 
				'label'   => '"Содержание"', 
				'rules'   => ''
			)
		);
		$this->form_validation->set_rules($term_rules);

		$data['term'] = $term;
		$data['title'] = 'Редактировать категорию "' . $term->name . '"';

		$terms = $this->query_tax->get_terms($taxonomy);

		$terms_names = array('0' => 'Нет');
		foreach ($terms as $term) 
		{
			$terms_names[$term->term_id] = $term->name;
		}

		$data['terms'] = $terms_names;

		$args['name'] = $this->input->post('name', TRUE);
		$args['slug'] = $this->input->post('slug', TRUE);
		$args['description'] = $this->input->post('desc', TRUE);
		$args['parent'] = $this->input->post('term_parent', TRUE);


		if($this->form_validation->run() == FALSE) 
		{
			$this->load_view('term_update', $data);
		}
		else 
		{
			$data['title'] = "Категория обновлена";
			$data['action'] = "Категория успешно обновлена.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');

			$this->query_tax->update_term($id, $taxonomy, $args);
			$this->load_view('success', $data);
		}
	}

	public function term_create()
	{
		$this->load->model('query_tax');
		$this->load->library('form_validation');
		$this->load->helper('form');

		$taxonomy = $this->default_taxonomy;
		$term_rules = array(
			array(
				'field'   => 'name', 
				'label'   => '"Название"', 
				'rules'   => 'required|is_unique[terms.name]'
			),
			array(
				'field'   => 'slug', 
				'label'   => '"Ярлык"', 
				'rules'   => 'alpha_dash'
			),
			array(
				'field'   => 'desc', 
				'label'   => '"Описание"', 
				'rules'   => ''
			)
		);
		$this->form_validation->set_rules($term_rules);		

		$terms = $this->query_tax->get_terms($taxonomy);

		$terms_names = array('0' => 'Нет');
		foreach ($terms as $term) 
		{
			$terms_names[$term->term_id] = $term->name;
		}

		$data['terms'] = $terms_names;
		$data['title'] = 'Создать новую категорию';

		$args['name'] = $this->input->post('name', TRUE);
		$args['slug'] = $this->input->post('slug', TRUE);
		$args['description'] = $this->input->post('desc', TRUE);
		$args['parent'] = $this->input->post('term_parent', TRUE);


		if($this->form_validation->run() == FALSE) 
		{
			$this->load_view('term_create', $data);
		}
		else 
		{
			$term_name = $this->input->post('name', TRUE);
			$slug = $this->input->post('slug', TRUE);
			$desc = $this->input->post('desc', TRUE);
			$parent = $this->input->post('term_parent', TRUE);

			$this->query_tax->insert_term($term_name, $taxonomy, array(
				'slug' => $slug,
				'description' => $desc,
				'parent' => $parent
				)
			);

			$data['title'] = "Категория создана";
			$data['action'] = "Категория успешно создана.";
			$data['reffer'] = $this->input->server('HTTP_REFERER');

			$this->load_view('success', $data);
		}
	}

	/* Служебные методы */
	public function _password_check($arg, $auth)
	{
		
		$auth = explode("/SEPARATOR/", $auth);
		if($this->user->check_login($auth[0], $auth[1])) 
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_password_check', 'Логин или пароль неверен');
			return FALSE;
		}
	}

	private function load_view($template, $data = NULL)
	{
		$this->load->view('admin/header', $data);
		$this->load->view('admin/' . $template);
		$this->load->view('admin/footer');
	}
}