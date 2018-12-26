<?php
	class Common_model extends CI_Model
	{
		public function __construct()
		{
			parent:: __construct();
		}
		public function get_data($data)
		{
			if(isset($data['where']) && $data['where'])
			{
				$this->db->where($data['where']);
			}
			if(isset($data['where_in']) && $data['where_in'])
			{
				$this->db->where_in($data['where_in_field'], $data['where_in']);
			}
			return $this->db->select($data['fields'])->from($data['table'])->get();
		}
		public function insert_data($data)
		{
			$this->db->insert($data['table'],$data['data']);
			return $this->db->insert_id();
		}
		public function update_data($data)
		{
			$this->db->where($data['where']);
			$this->db->update($data['table'],$data['data']);
			return $this->db->affected_rows();
		}
		public function delete_data($data)
		{
			
			if($data['type'] == 'update')
			{
				return $this->update_data($data);
			}
			else
			{
				$this->db->where($data['where']);
				return $this->db->delete($data['table']);
			}
			
		}
	}
?>