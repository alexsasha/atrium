<?php 

class Query extends CI_Model {
	var $post_table;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->post_table = 'posts';
    	$this->db->dbprefix($this->post_table);
	}

	public function pars_args($args, $defaults) {
		if (is_array($defaults))
			return array_merge($defaults, $args);
	}

	public function rus2translit($string) 
	{
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }


	public function get_posts($args = array())
    {
    	$defaults = array(  
	        'numberposts' => 10, 
	        'offset' => 0,  
	        'orderby' => 'post_date',  
	        'order' => 'DESC',
	        'post_status' => 'publish'
	    ); 
    	$r = $this->pars_args($args, $defaults);
    	$table = $this->post_table;

    	if($r['numberposts'] != -1)
    		$this->db->limit($r['numberposts'], $r['offset']);
    	

    	$this->db->where('post_status', $r['post_status']);
    	$this->db->order_by($r['orderby'], $r['order']);
    	$query = $this->db->get($table);
    	
    	return $query->result();
    }

    public function get_posts_number()
    {
    	$table = $this->post_table;
    	$count = $this->db->count_all($table);
    	return $count;
    }

    public function update_post($args = array()) 
    {
    	$current_date = date("Y-m-d H:i:s");
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
	    	$defaults = array(
		        'post_content' => '',
		        'post_title' => '',
		        'post_name' => '',
		        'post_status' => 'publish',
		        'post_date' => $current_date,
		        'post_modified' => $current_date
		    );
		    $r = $this->pars_args($args, $defaults);
		    
		    $this->load->helper('url');
		    if($r['post_name'] === '' && $r['post_title'] !== '')
				$r['post_name'] = url_title($this->rus2translit($r['post_title']), '-', TRUE);

			$this->db->insert($table, $r);
	    }
    }
}