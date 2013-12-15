<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Config extends CI_Config {

	var $option_table;

    function __construct()
    {
        parent::__construct();
       	$this->option_table = 'options';
    }

    public function get($option)
    {
    	$CI =& get_instance(); 
		$CI->load->database();

		$table = $this->option_table;
	   	$CI->db->dbprefix($table);

    	if($option)
		{
	        $query = $CI->db->get_where(
	        	$table, 
	        	array(
	        		'option_name' => $option
	        	)
	    	);
	        $r = $query->result();
	        if($r) 
	        {
		        $r = $r[0];
		    	return $r->option_value;
	        }
		}
    }

    public function set($option, $value)
	{
		if($option && $value)
		{
			$CI =& get_instance(); 
			$CI->load->database();

			$table = $this->option_table;
			$option_data = array(
				'option_name' => $option,
				'option_value' => $value
			);

	   		$CI->db->dbprefix($table);
			$CI->db->select('option_id');

	        $query = $CI->db->get_where(
	        	$table, 
	        	array(
	        		'option_name' => $option
	        	)
	    	);

	        $option_aux = $query->result();

	    	if($option_aux) //обновляем
	    	{
	    		$option_aux = $option_aux[0];
	    		$CI->db->update($table, $option_data, array('option_id' => $option_aux->option_id));
	    	}
	    	else //добавляем
	    	{
	    		$CI->db->insert($table, $option_data);
	    	}
		}
	}
}

// END MY_Config class

/* End of file MY_Config.php */
/* Location: ./application/core/MY_Config.php */