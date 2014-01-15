<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Config extends CI_Config {

	var $option_table = 'options';

    function __construct()
    {
        parent::__construct();
    }

    public function get($option = NULL)
    {
    	if($option === NULL)
    		return FALSE;

    	$CI =& get_instance(); 
		$CI->load->database();

		$table = $this->option_table;
	   	$CI->db->dbprefix($table);

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

    public function set($option = NULL, $value = NULL)
	{
		if($option === NULL || $value === NULL)
			return FALSE;

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

    	if($option_aux)
    	{
    		$option_aux = $option_aux[0];
    		$CI->db->update($table, $option_data, array('option_id' => $option_aux->option_id));
    	}
    	else
    	{
    		$CI->db->insert($table, $option_data);
    	}
	}
}

// END MY_Config class

/* End of file MY_Config.php */
/* Location: ./application/core/MY_Config.php */