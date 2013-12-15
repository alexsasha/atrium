<?php 

class Query extends CI_Model {

    var $timezones, $date_format;
    var $post_table = 'posts'; 
    var $post_statuses = array('trash', 'publish');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->db->dbprefix($this->post_table);

        $this->timezones = $this->config->get("timezones");        
        $this->date_format = $this->config->get("date_format");        
	}

	public function get_posts($args = array())
    {
        $this->load->helper('array');

    	$defaults = array(  
	        'numberposts' => 10, 
	        'offset' => 0,  
	        'orderby' => 'post_date',
	        'order' => 'DESC',
            'post_status' => 'publish'
	    ); 
    	$r = pars_args($args, $defaults);
    	$table = $this->post_table;

    	if($r['numberposts'] != -1)
    		$this->db->limit($r['numberposts'], $r['offset']);
    	$this->db->order_by($r['orderby'], $r['order']);
        
        unset($r['orderby'], $r['order'], $r['offset'], $r['numberposts']);
    	
        $query = $this->db->get_where($table, $r);
    	
    	return $query->result();
    }

    public function get_user_posts_number($user_id)
    {
        return $this->get_posts_number(array('post_author' => $user_id));
    }

    public function get_post($id)
    {
    	$table = $this->post_table;
    	$query = $this->db->get_where($table, array("ID" => $id));
        
        $r = $query->result();
        if($r)
    	   return $r[0];

        return FALSE;
    }


    public function get_posts_number($args = array())
    {
        $args['numberposts'] = -1;
    	$posts = $this->get_posts($args);
    	
        return count($posts);
    }

    public function update_post($args = array()) 
    {
        $date_format = 'Y-m-d H:i:s'; //use defined date format for DB
    	$current_date = gmdate($date_format);
    	$table = $this->post_table;
    	
	    //если определен ID, то обновляем запись
	    if(isset($args['ID']))
	    {
	    	$ID = $args['ID'];
	    	$args['post_modified'] = $current_date;
	    	$this->db->update($table, $args, array('ID' => $ID));
	    }
	    else
	    {
            $this->load->helper(array('array', 'url'));

	    	$defaults = array(
		        'post_content' => '',
		        'post_title' => '',
		        'post_name' => '',
		        'post_status' => 'publish',
		        'post_date' => $current_date,
		        'post_modified' => $current_date
		    );
		    $r = pars_args($args, $defaults);
		    
		    $this->load->helper('url');
		    if($r['post_name'] === '' && $r['post_title'] !== '')
				$r['post_name'] = url_title(rus2translit($r['post_title']), '-', TRUE);

			$this->db->insert($table, $r);
	    }
    }

    public function delete_post($id)
    {
        if($this->post_exists($id)) 
        {
            $table = $this->post_table;
            return $this->db->delete($table, array('ID' => $id)); 
        } 
        else 
        {
            return FALSE;
        }
    }

    public function change_post_status($id, $status)
    {
        if($this->post_exists($id) && in_array($status, $this->post_statuses)) 
        {
            $table = $this->post_table;
            $args['post_status'] = $status;
            $this->db->update($table, $args, array('ID' => $id));
        } 
        else 
        {
            return FALSE;
        }
    }

    public function get_post_date($post_id, $date_format = NULL)
    {
        $post = $this->get_post($post_id);
        if( ! $post)
            return FALSE;
        if( ! $date_format)
            $date_format = $this->date_format;

        $timezones = $this->timezones;
        $this->load->helper('date');

        return gmt_to_local_date($date_format, $post->post_date, $timezones);
    }

    public function post_exists($id) 
    {
        $table = $this->post_table;
        $this->db->select('ID');
        $query = $this->db->get_where($table, array("ID" => $id));
    
        return $query->result();
    }
}