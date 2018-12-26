<?php
	/**
	* Services model
	*/
	class Service_model extends CI_Model
	{
		
		public function __construct()
		{
			parent:: __construct();
		}
		public function get_invited_users($contacts, $mobile_number)
		{
			return $this->db->query("SELECT mobile_number FROM vt_invited_numbers WHERE mobile_number IN(".$contacts.") AND invited_by=".$mobile_number)->result();
		}
		public function get_app_users($contacts, $user_id)
		{
			if($user_id)
				$query = "AND (t2.user1=".$user_id." OR t2.user2=".$user_id.") ";
			else
				$query = "AND (t2.user1='0' OR t2.user2='0') ";
			return $this->db->query("SELECT t1.id AS user_id,
											t1.mobile_number, 
											t1.first_name, 
											t1.last_name, 
											t1.user_image, 
											t1.uid, 
											t2.chat_id, 
											t2.chat_type 
											FROM vt_otp_validation t1 
											LEFT JOIN vt_chat_list t2 ON (t1.id=t2.user1 OR t1.id=t2.user2) ".$query."
											WHERE t1.mobile_number IN(".$contacts.") 
											AND t1.status=1 
											AND t1.is_registered=1")->result();
		}
		public function get_chats($data)
		{
			return $this->db->query("SELECT t2.id AS user_id,
											t2.first_name,
											t2.last_name,
											t2.user_image,
											t2.mobile_number, 
											t1.chat_id,
											t1.chat_type  
											FROM vt_chat_list t1 
											LEFT JOIN vt_otp_validation t2 ON t1.user1=t2.id OR t1.user2=t2.id 
											WHERE t1.user1=".$data['user_id']." 
											OR t1.user2=".$data['user_id']);
		}
	}
?>