<?php
	class Login_model extends CI_Model
	{
		public function __construct()
		{
			parent:: __construct();
		}
		public function login($data)
		{
			$where = array('email' => $data['email'], 'password' => $data['password']);
			return $this->db->select('id,status')->from('login')->where($where)->get()->row();
		}
	}
?>