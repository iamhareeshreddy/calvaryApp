<?php 
class Voilet_model extends CI_Model
{
	public function __construct()
	{
		parent:: __construct();
	}
	public function get_fads()
	{
		return $this->db->query("SELECT 
							t1.fad_name,
							t1.fad_description,
							t1.created_at,
							t1.id,
							t1.status,
							t2.category_name  
							FROM vt_fads t1 
							LEFT JOIN vt_fad_categories t2 
							ON t1.category=t2.id 
						    WHERE t1.status=1");
	}
}

?>