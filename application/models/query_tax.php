<?php 

class Query_tax extends CI_Model {
	
	var $term_relationships_table = 'term_relationships';
	var $terms_table = 'terms'; 

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

	public function get_terms($taxonomy = 'category', $parent = 0)
	{
		$table = $this->terms_table;
		$this->db->dbprefix($table);

		$query = $this->db->get_where($table, array(
			'taxonomy' => $taxonomy, 
			'parent' => $parent
			)
		);
    	
    	return $query->result();

	}

	public function insert_term($term, $taxonomy, $args)
	{
		$this->load->helper(array('url', 'array'));
		
		$table = $this->terms_table;
		$this->db->dbprefix($table);
	    
		if( ! $taxonomy)
			$taxonomy = 'category';

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
		   'parentparent' => $parent,
		   'count' => 0
		);

		$this->db->insert($table, $data);
	}

	public function term_exists($term, $taxonomy, $field = 'name')
	{
		if( ! $taxonomy)
			$taxonomy = 'category';

		$table = $this->terms_table;
		$this->db->dbprefix($table);
		
		$query = $this->db->get_where($table, array(
			$field => $term,
			'taxonomy' => $taxonomy
			)
		);

		return $query->result();
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

	public function unique_term_slug($slug)
	{
		if ( ! $this->term_exists($slug, '', 'slug'))
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
}