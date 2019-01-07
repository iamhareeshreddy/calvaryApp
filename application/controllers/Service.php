<?php
	/**
	* servicess controller
	*/
	class Service extends CI_Controller
	{
		
		function __construct()
		{
			parent:: __construct(); 
			error_reporting(0);
        }
		public function getOtpSample()
		{
			$this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[9]');
            $this->form_validation->set_rules('country_code', 'Country code', 'required');
			$this->form_validation->set_rules('otp_for', 'Otp for', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                   $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                set_phone_number_length(strlen(str_replace($this->input->post('country_code'), '', trim_number($this->input->post("phone_number")))));
                set_default_code($this->input->post('country_code'));
                $country_code = $this->input->post('country_code')?$this->input->post('country_code'):DEFAULT_CODE;
                if(strpos(trim_number($this->input->post("phone_number")), '+') !== false)
                {
                    $phone_number = remove_zero($this->input->post("phone_number"));
                }
                else
                {
                    $phone_number = DEFAULT_CODE.trim_number($this->input->post("phone_number"));
                }
            	$otp_code = rand(100000,999999); 
				$result = $this->common_model->get_data(array("fields" => "id, country_code, otp, is_registered, status","table" => "otp_validation", "where" => array("mobile_number"	=> $phone_number)));
				if($result->num_rows()>0)
				{
                    $row = $result->row();
					if($this->input->post('otp_for') == 'login')
					{
                        if($row->is_registered == 1)
                        {
    						$this->common_model->update_data(array("table" => "otp_validation", "where" => array("mobile_number" => $phone_number), "data" => array("otp"	=> $otp_code,"status" => 0)));
    						$response = array("response_info" => 1, "response_message" =>  "Success", "data" => array("otp_code" => $otp_code));
                        }
                        else
                        {
                            $response = array("response_info" => 0, "response_message" =>  "Registration not completed yet.");
                        }
					}
					else
					{
                        if($row->is_registered == 0)
                        {
                            $array = array("table" => "otp_validation", "where" => array("mobile_number" => $phone_number));
                            $this->common_model->delete_data($array);
                            $array = array(
                                        "table"     => "otp_validation",
                                        "data"      => array(   
                                                                "mobile_number" => $phone_number,
                                                                "country_code"  => $row->country_code,
                                                                "otp"           => $otp_code,
                                                                "status"        => 0,
                                                                "created_at"    => date("Y-m-d H:i:s")
                                                            )
                                    );
                            $insert_id = $this->common_model->insert_data($array);
                            if($insert_id)
                            {
                                $response = array("response_info" => 1, "response_message" =>  "Success", "data" => array("otp_code" => $otp_code));
                            }
                            else
                            {
                                $response = array("response_info" => 0, "response_message" =>  "Somthing went wrong please try again...");
                            }
                        }
                        else
                        {
                            $response = array("response_info" => 0, "response_message" =>  "mobile number is exists.");
                        }
					}
				}
				else
				{
                    if($this->input->post('otp_for') == 'new')
					{
                        $array = array(
										"table"		=> "otp_validation",
										"data"		=> array(	
																"mobile_number"	=> $phone_number,
																"otp"			=> $otp_code,
																"status"		=> 0,
																"created_at"	=> date("Y-m-d H:i:s"),
                                                                "country_code"  => $this->input->post('country_code')?$this->input->post('country_code'):DEFAULT_CODE
															)
									);
						$insert_id = $this->common_model->insert_data($array);
						if($insert_id)
						{
							$response = array("response_info" => 1, "response_message" =>  "Success", "data" => array("otp_code" =>$otp_code));
						}
						else
						{
							$response = array("response_info" => 0, "response_message" =>  "Somthing went wrong please try again...");
						}
					}
					else
					{
						$response = array("response_info" => 0, "response_message" =>  "you are not registered please signup ");
					}
				}
			}
			echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
		}
		public function validateOtpSample()
		{
			$this->form_validation->set_rules('otp_code', 'OTP', 'required|min_length[6]|max_length[6]');
			$this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[9]');
            $this->form_validation->set_rules('country_code', 'Country code', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                set_phone_number_length(strlen(str_replace($this->input->post('country_code'), '', trim_number($this->input->post("phone_number")))));
                set_default_code($this->input->post('country_code'));
                if(strpos(trim_number($this->input->post("phone_number")), '+') !== false)
                {
                    $phone_number = remove_zero($this->input->post("phone_number"));
                }
                else
                {
                    $phone_number = DEFAULT_CODE.trim_number($this->input->post("phone_number"));
                }
                $array = array(
            						"fields"			=> "id, country_code, status, user_email, user_password, first_name, last_name, user_image",
            						"table"				=> "otp_validation",
            						"where"				=> array(
            														"mobile_number"		=> $phone_number,
            														"otp"				=> $this->input->post('otp_code')
            													)
            					);
            	$result = $this->common_model->get_data($array);
                if($result->num_rows()>0)
            	{
            		$row = $result->row();
            		if($row->status == 0)
            		{
            			$array['data']	= array("status" => 1);
            		    if($this->common_model->update_data($array))
                        {
                            $response = array(
                                                "response_info"     => 1, 
                                                "response_message"  =>  "Otp is successfully verified", 
                                                "data"              => array(
                                                                                "user_id"       => $row->id,
                                                                                "email_id"      => $row->user_email, 
                                                                                "password"      => $row->user_password,
                                                                                "first_name"    => $row->first_name,
                                                                                "last_name"     => $row->last_name,
                                                                                "user_image"    => $row->user_image
                                                                            )
                                            );
                        }
                        else
                        {
                            $response = array("response_info" => 0, "response_message" =>  "Something went wrong please try again.");
                        }
            		}
            		else
            		{
            			$response = array("response_info" => 0, "response_message" =>  "otp expired");
            		}
            	}
            	else
            	{
            		$response = array("response_info" => 0, "response_message" =>  "Wrong otp code");
            	}
            }
            echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
		}
		public function checkContacts()
		{
            $this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[9]');
            $this->form_validation->set_rules('country_code', 'Country code', 'required');
			$this->form_validation->set_rules('contacts_list', 'Contacts Array', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                set_phone_number_length(strlen(str_replace($this->input->post('country_code'), '', trim_number($this->input->post("phone_number")))));
                set_default_code($this->input->post('country_code'));
                if(strpos(trim_number($this->input->post("phone_number")), '+') !== false)
                {
                    $phone_number = remove_zero($this->input->post("phone_number"));
                }
                else
                {
                    $phone_number = DEFAULT_CODE.trim_number($this->input->post("phone_number"));
                }
                $contacts_list = json_decode($this->input->post('contacts_list'));
            	foreach($contacts_list as $contact)
            	{
                    if(strlen($contact->PhoneNumber) >= 9 && strlen($contact->PhoneNumber) <= 14)
                    {
                        if(strpos($contact->PhoneNumber, '+') !== false)
                        {
                            $contact_number = preg_replace('/\s+/', '', trim_number($contact->PhoneNumber));
                        }
                        else
                        {
                            $contact_number = DEFAULT_CODE.preg_replace('/\s+/', '', trim_number($contact->PhoneNumber));
                        }
            			$contacts_numbers[] = $contact_number;
                    }
            	}
                if(count($contacts_numbers) >0)
                {
                    $contacts = implode(',', $contacts_numbers);
                    $user_id = $this->common_model->get_data(array("table" => "otp_validation", "fields" => 'id', "where" => array("mobile_number" => $phone_number)))->row()->id;
                    $app_users = $this->Service_model->get_app_users($contacts, $user_id);
                    $userdata = array();
                    foreach ($app_users as $user)
                    {
                        $app_users_data[] = $user->mobile_number;
                        $userdata[$user->mobile_number] = array(
                                                                    "user_id"       => $user->user_id,
                                                                    "first_name"    => $user->first_name, 
                                                                    "last_name"     => $user->last_name, 
                                                                    "user_image"    => $user->user_image,
                                                                    "uid"           => $user->uid,
                                                                    "chat_id"       => $user->chat_id,
                                                                    "chat_type"     => $user->chat_type
                                                                );
                    }
                    $invited_users = $this->Service_model->get_invited_users($contacts, $phone_number);
                    foreach ($invited_users as $user)
                    {
                        $invited_users_data[] = $user->mobile_number;
                    }
                    $data = array();
                    foreach($contacts_list as $contact)
                    {
                        if(strpos($contact->PhoneNumber, '+') !== false)
                        {
                            $contact_number = preg_replace('/\s+/', '', trim_number($contact->PhoneNumber));
                        }
                        else
                        {
                            $contact_number = DEFAULT_CODE.preg_replace('/\s+/', '', trim_number($contact->PhoneNumber));
                        }
                        if($phone_number != $contact_number && strlen($contact->PhoneNumber) >8 && strlen($contact->PhoneNumber) <15)
                        {
                            if(in_array($contact_number, $app_users_data))
                            {
                                $array = array(
                                                "IsInvit"       => 0,
                                                "user_id"       => $userdata[$contact_number]['user_id'],
                                                "IsVioletUser"  => 1, 
                                                "PhoneNumber"   => $contact_number, 
                                                "fullName"      => $userdata[$contact_number]['first_name'].' '.$userdata[$contact_number]['last_name'],
                                                "chat_data"     =>  $userdata[$contact_number]['chat_id']?1:0,
                                                "userdata"      => $userdata[$contact_number]
                                                );
                            }
                            else if(in_array($contact_number, $invited_users_data))
                            {
                                $array = array("IsInvit" => 1, "IsVioletUser" => 0, "PhoneNumber" => $contact_number, "fullName" => $contact->fullName);
                            }
                            else
                            {
                                $array = array("IsInvit" => 0, "IsVioletUser" => 0, "PhoneNumber" => $contact_number, "fullName" => $contact->fullName);
                            }
                            array_push($data, $array);
                        }
                    }
                    $response = array("response_info" => 1, "response_message" =>  "Success", "data" => $data);
                }
            	else
                {
                    $response = array("response_info" => 0, "response_message" =>  "No data founnd");
                }
            	echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
            }
		}
		public function userRegistered()
		{
            $this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[9]');
            $this->form_validation->set_rules('country_code', 'Country code', 'required');
            $this->form_validation->set_rules('userdata', 'User data array', 'required');
            if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                set_phone_number_length(strlen(str_replace($this->input->post('country_code'), '', trim_number($this->input->post("phone_number")))));
                set_default_code($this->input->post('country_code'));
                if(strpos(trim_number($this->input->post("phone_number")), '+') !== false)
                {
                    $phone_number = remove_zero($this->input->post("phone_number"));
                }
                else
                {
                    $phone_number = DEFAULT_CODE.trim_number($this->input->post("phone_number"));
                }
                $userdata = json_decode($this->input->post('userdata'),true);
                $array = array(
                                    "table" => "otp_validation", 
                                    "data"  => array(
                                                        "is_registered" => 1,
                                                        "uid"           => $userdata['uid'],
                                                        "user_email"    => $userdata['email_id'], 
                                                        "user_password" => $userdata['password'],
                                                        "first_name"    => $userdata['first_name'], 
                                                        "last_name"     => $userdata['last_name'], 
                                                        "user_image"    => $userdata['user_image'] 
                                                    ), 
                                    "where" => array("mobile_number" => $phone_number)
                                );
                if($this->common_model->update_data($array))
                {
                    $array = array("table" => "invited_numbers", "where" => array("mobile_number" => $phone_number));
                    $this->common_model->delete_data($array);
                    $response = array("response_info" => 1, "response_message" =>  "Success");
                }
                else
                {
                   $response = array("response_info" => 0, "response_message" =>  "Mobile number doesn't exist or nothing changed");
                }
            }
              echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
		}
        public function inviteUser()
        {
            $this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[9]');
            $this->form_validation->set_rules('country_code', 'Country code', 'required');
            $this->form_validation->set_rules('invited_by', 'Invited by', 'required|min_length[9]|integer');
            if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                set_phone_number_length(strlen(str_replace($this->input->post('country_code'), '', trim_number($this->input->post("phone_number")))));
                set_default_code($this->input->post('country_code'));
                if(strpos(trim_number($this->input->post("phone_number")), '+') !== false)
                {
                    $phone_number = $this->input->post("phone_number");
                }
                else
                {
                    $phone_number = DEFAULT_CODE.trim_number($this->input->post("phone_number"));
                }
                if(strpos($this->input->post("invited_by"), '+') !== false)
                {
                    $invited_by = remove_zero($this->input->post("invited_by"));
                }
                else
                {
                    $invited_by = DEFAULT_CODE.trim_number($this->input->post("invited_by"));
                }
                $res = $this->common_model->get_data(array("fields" => 'auto_id' ,"table" => "invited_numbers", "where" => array("mobile_number" => $phone_number, "invited_by" => $invited_by)));
                if($res->num_rows()>0)
                {
                    $response = array("response_info" => 0, "response_message" =>  "You have already sent invitation");
                }
                else
                {
                   $array = array(   
                                    "table" => "invited_numbers", 
                                    "data" => array(
                                                        "mobile_number"     => $phone_number,
                                                        "invited_by"        => $invited_by,
                                                        "invited_at"        => date("Y-m-d H:i:s")
                                                    )
                                   );
                    $insert_id = $this->common_model->insert_data($array);
                    if($insert_id)
                    {
                        $data = array(
                                        "PhoneNumber"   => trim_number($this->input->post("phone_number")), 
                                        "invited_by"    => $this->input->post("invited_by"),
                                        "IsInvit"       => 1,
                                        "IsVioletUser"  => 0,
                                        "fullName"      => $this->input->post('fullName')
                                    );
                        $response = array("response_info" => 1, "response_message" =>  "Success", "data" => $data);
                    }
                    else
                    {
                          $response = array("response_info" => 0, "response_message" =>  "Somthing went wrong please try again...");
                    } 
                }
                
            }
            echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
        }
        public function updateUserData()
        {
            $this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[9]');
            $this->form_validation->set_rules('userdata', 'User data array', 'required|min_length[10]');
            if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                if(strpos(trim_number($this->input->post("phone_number")), '+') !== false)
                {
                    $phone_number = trim_number($this->input->post("phone_number"));
                    $userdata = json_decode($this->input->post('userdata'),true);
                    $array = array(
                                        "table" => "otp_validation", 
                                        "data"  => array(
                                                            "user_email"    => $userdata['email_id'], 
                                                            "user_password" => $userdata['password'],
                                                            "first_name"    => $userdata['first_name'], 
                                                            "last_name"     => $userdata['last_name'], 
                                                            "user_image"    => $userdata['user_image'] 
                                                        ), 
                                        "where" => array("mobile_number" => $phone_number)
                                    );
                    if($this->common_model->update_data($array))
                    {
                        $response = array("response_info" => 1, "response_message" =>  "Success");
                    }
                    else
                    {
                       $response = array("response_info" => 0, "response_message" =>  "Mobile number doesn't exist");
                    }
                }
                else
                {
                    $response = array("response_info" => 0, "response_message" =>  "Missing country code in mobile number");
                }
            }
            echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
        }
        public function createChatList()
        {
            $this->form_validation->set_rules('user1', 'User1', 'required');
            $this->form_validation->set_rules('user2', 'User2', 'required');
            $this->form_validation->set_rules('chat_id', 'Chat id', 'required');
            $this->form_validation->set_rules('chat_type', 'Chat Type', 'required');
            if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                $data = array(
                                    "user1"         => $this->input->post("user1"),
                                    "user2"         => $this->input->post("user2"),
                                    "chat_id"       => $this->input->post("chat_id"),
                                    "chat_type"     => $this->input->post("chat_type"),
                                    "created_at"    => date("Y-m-d H:i:s")
                            );
                $insert_data = array(
                                        "table"     => "chat_list",
                                        "data"      => $data
                    );
                if($this->common_model->get_data(array("fields" => 'id', "table" => "chat_list", "where" => array("chat_id" => $this->input->post("chat_id"))))->num_rows() == 0)
                {
                    if($this->common_model->insert_data($insert_data))
                    {
                        $response = array("response_info" => 1, "response_message" =>  "Success");
                    }
                    else
                    {
                        $response = array("response_info" => 0, "response_message" =>  "Somthing went wrong please try again");
                    }
                }
                else
                {
                    $response = array("response_info" => 0, "response_message" =>  "110");
                }
            }
            echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
        }
        public function getChats()
        {
            $this->form_validation->set_rules('user_id', 'User Id', 'required');
            if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                $array = array("user_id" => $this->input->post("user_id"));
                $result = $this->Service_model->get_chats($array);
                if($result->num_rows()>0)
                {
                    $data = array();
                    foreach($result->result() as $row)
                    {
                        $push_data = array(
                                        "user_id"           => $row->user_id,
                                        "first_name"        => $row->first_name,
                                        "last_name"         => $row->last_name,
                                        "user_image"        => $row->user_image,
                                        "mobile_number"     => $row->mobile_number,
                                        "chat_id"           => $row->chat_id,
                                        "chat_type"         => $row->chat_type
                                    );
                        array_push($data, $push_data);
                    }
                    $response = array("response_info" => 1, "response_message" =>  "Success", "data" => $data);
                }
                else
                {
                    $response = array("response_info" => 0, "response_message" =>  "Chats not available");
                }
            }
            echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
        }
        public function test($strem=null)
        {
            if($strem)
            {
                $file = FCPATH."uploads/albums/audio/2/bensound-extremeaction.mp3";

                $extension = "mp3";
                $mime_type = "audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3";

                if(file_exists($file)){
                    header('Content-type: {$mime_type}');
                    header('Content-length: ' . filesize($file));
                    header('Content-Disposition: filename="' . $filename);
                    header('X-Pad: avoid browser bug');
                    header('Cache-Control: no-cache');
                    readfile($file);
                }else{
                    header("HTTP/1.0 404 Not Found");
                }
            }
            else
            {
                $this->load->view("welcome_message");
            }
        }
    }
?>