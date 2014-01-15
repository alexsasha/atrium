<?php 

class Query_tax extends CI_Model {
	
	var $term_relationships_table = 'term_relationships',
	$terms_table = 'terms'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function update_term($term_id, $taxonomy = 'category', $args)
	{
		$table = $this->terms_table;
		$this->db->dbprefix($table);

		$this->db->update($table, $args, array('term_id' => $term_id));
	}

	public function get_terms($taxonomy = 'category', $parent = NULL)
	{
		$table = $this->terms_table;
		$this->db->dbprefix($table);

		if($parent === NULL)
		{
			$where = array(
				'taxonomy' => $taxonomy
			);
		}
		else
		{
			$where = array(
				'taxonomy' => $taxonomy, 
				'parent' => $parent
			);
		}

		$query = $this->db->get_where($table, $where);
    	
    	return $query->result();
	}

	public function insert_term($term, $taxonomy = 'category', $args)
	{
		$this->load->helper(array('url', 'array'));
		
		$table = $this->terms_table;
		$this->db->dbprefix($table);

	    $name = $term;
		$term = $this->term_exists($name, $taxonomy);
		
		if($term)
			return FALSE;
	    
		$defaults = array(
	        'slug' => '',
	        'parent' => 0,
	        'description' => ''
	    );

	    $r = pars_args($args, $defaults);

	    extract($r, EXTR_SKIP);

	    if( ! $slug)
	    	$slug = sanitize($name);

	    $slug = $this->unique_term_slug($slug);
		
		$data = array(
		   'taxonomy' => $taxonomy,
		   'name' =>  $name,
		   'slug' => $slug,
		   'description' => $description,
		   'parent' => $parent,
		   'count' => 0
		);

		if($this->db->insert($table, $data))
        {
            return $this->db->insert_id();
        }
	}

	public function term_exists($term, $taxonomy = 'category', $field = 'name')
	{

		$table = $this->terms_table;
		$this->db->dbprefix($table);
		
		$query = $this->db->get_where($table, array(
			$field => $term,
			'taxonomy' => $taxonomy
			)
		);

		$result = $query->result();
		if($result)
			return $result[0];

		return FALSE;
	}

	public function delete_term($id)
	{
        $table = $this->terms_table;
		$this->db->dbprefix($table);

        if($this->db->get_where($table, array('term_id' => $id))->result()) 
        {
            return $this->db->delete($table, array('term_id' => $id));
        } 
        else 
        {
            return FALSE;
        }
	}

	public function unique_term_slug($slug, $taxonomy = 'category')
	{
		if ( ! $this->term_exists($slug, $taxonomy, 'slug'))
			return $slug;
		
		$num = 2;
		$table = $this->terms_table;
		$this->db->dbprefix($table);

		do {
			$alt_slug = $slug . "-$num";
			$num++;
			
			$query = $this->db->get_where($table, array(
				'slug' => $alt_slug
				)
			);
		} while ($query->result());
		$slug = $alt_slug;
		
		return $slug;
	}

	public function get_term_children($term_id, $taxonomy = 'catrgory') 
	{
		$term_id = intval($term_id);

		$terms = $this->_get_term_hierarchy($taxonomy);

		if ( ! isset($terms[$term_id]))
			return array();

		$children = $terms[$term_id];

		foreach ((array) $terms[$term_id] as $child) {
			if (isset($terms[$child]))
				$children = array_merge($children, $this->get_term_children($child, $taxonomy));
		}

		return $children;
	}

	public function set_post_terms($post_id, $term_ids) 
	{
		if( ! is_array($term_ids))
			return FALSE;

		$table = $this->term_relationships_table;
		$posts = $this->db->get_where($table, array('object_id' => $post_id));
		$posts = $posts->result();
		$add_term_ids = $term_ids;

		if($posts)
		{
			foreach ($posts as $post) 
			{
				if(in_array($post->term_id, $term_ids))
				{
					unset($add_term_ids[array_search($post->term_id, $add_term_ids)]);
				}
				else
				{
					$this->db->delete($table, array(
						'term_id' => $post->term_id,
						'object_id' => $post_id
						)
					);
				}
			}
		}

		foreach ($add_term_ids as $id) 
		{
			$this->db->insert($table, array(
				'object_id' => $post_id, 
				'term_id' => $id
				)
			);
		}
	}

	public function get_terms_number($taxonomy = 'category') 
	{
		$terms = $this->get_terms($taxonomy);
        
        if($terms)
        {
        	return count($terms);
        }
        else
        {
        	return 0;
        }
	}

	public function get_post_terms($post_id) 
	{
		$table = $this->term_relationships_table;
		$this->db->select('term_id');
		$query = $this->db->get_where($table, array('object_id' => $post_id));
    	
    	return $query->result();
	}

	private function _get_term_hierarchy($taxonomy)
	{
		$children = array();
		$terms = $this->get_terms($taxonomy);
		foreach ( $terms as $term ) 
		{
			if ( $term->parent > 0 )
				$children[$term->parent][] = $term->term_id;
		}

		return $children;
	}
}