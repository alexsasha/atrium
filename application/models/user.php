<?php 

class User extends CI_Model {
	
	var $user_table = 'users',
	$user_roles = array(
		0 => 'guest',
		1 => 'admin'
	),
	$default_user_status = 0;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
    	$this->db->dbprefix($this->user_table);
   	}

	public function get_user_by($field, $value) 
	{
    	$table = $this->user_table;

		switch ($field) {
			case 'id':
		    	$table_filed = 'ID';
				break;
			case 'login':
				$table_filed = 'user_login';
				break;
			case 'email':
				$table_filed = 'user_email';
				break;
			default:
				return FALSE;
				break;
		}

		$query = $this->db->get_where($table, array($table_filed => $value));
		        
        $r = $query->result();
        if($r)
    	   return $r[0];

		return FALSE;
	}

	public function get_user($user_id)
	{
		return $this->get_user_by('id', $user_id);
	}

	public function get_users()
	{
    	$table = $this->user_table;

		$query = $this->db->get($table);
		        
        $r = $query->result();
        if($r)
    	   return $r;

		return FALSE;
	}

	public function get_users_number() 
	{
		$terms = $this->get_users();
        
        if($terms)
        {
        	return count($terms);
        }
        else
        {
        	return 0;
        }
	}

	public function update_user($args) 
	{
		$date_format = 'Y-m-d H:i:s'; //use defined date format for DB
    	$table = $this->user_table;

	    if(isset($args['ID']))
	    {
	    	$ID = $args['ID'];
	    	$this->db->update($table, $args, array('ID' => $ID));
	    }
	    else
	    {
            $this->load->helper(array('array', 'url'));
    		$current_date = gmdate($date_format);

	    	$defaults = array(
		        'user_registered' => $current_date,
		        'user_status' => $this->default_user_status
		    );

		    $r = pars_args($args, $defaults);
		    
			if($this->db->insert($table, $r))
	        {
	            return $this->db->insert_id();
	        }
	    }
	}

	public function delete_user($user_id)
	{
		$user = $this->get_user($user_id);
		if($user) 
        {
            $table = $this->user_table;
            $this->db->delete($table, array('ID' => $user_id)); 
        } 
        else 
        {
            return FALSE;
        }
	}

	public function check_login($login, $pass) 
	{

		if( ! $login || ! $pass)
			return FALSE;

		$this->load->library('encrypt');

		$table = $this->user_table;

        $query = $this->db->get_where(
        	$table, 
        	array(
        		'user_login' => $login
        	)
    	);

    	$user = $query->result();
    	if( ! $user)
    		return FALSE;
    	
    	$user = $user[0];

    	$encrypted_pass = $user->user_pass;
    	$decoded_pass = $this->encrypt->decode($encrypted_pass);

    	if($decoded_pass === $pass)
        	return $user;

        return FALSE;
	}

	public function authenticate($login, $pass)
	{
		$user = $this->check_login($login, $pass);
		if( ! $user)
			return FALSE;
		
		$this->load->library('encrypt');

		$userdata = array(
          	'ID'          => $user->ID,
          	'user_login'  => $user->user_login,
           	'user_status' => $user->user_status,
           	'logged_in'   => TRUE
       	);

		return $this->session->set_userdata($userdata);
	}

	public function de_authenticate()
	{
		$userdata['user_login'] = '';
		$userdata['logged_in'] = '';
		$userdata['user_status'] = '';

		return $this->session->unset_userdata($userdata);
	}

	public function is_user_logged_in() 
	{

		return $this->session->userdata('logged_in');
	}

	public function user_role($user_status = NULL)
	{
		$user_roles = $this->user_roles;

    	if($user_status !== NULL && $user_roles)
    		return $user_roles[$user_status];
	}

	public function is_admin() 
	{
		$user_status = $this->session->userdata('user_status');

		return $this->is_user_logged_in() &&
				$this->user_role($user_status) == 'admin';
	}
}