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
            define("PHONE_NUMBER_LENGTH", 10);
		}
		public function getOtpSample()
		{
			$this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[10]|integer');
			$this->form_validation->set_rules('otp_for', 'Otp for', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                   $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                $phone_number = substr(preg_replace('/[^0-9\-]/', '', $this->input->post('phone_number')), -PHONE_NUMBER_LENGTH);
            	$otp_code = rand(100000,999999); 
				$result = $this->common_model->get_data(array("fields" => "id, otp, is_registered, status","table" => "otp_validation", "where" => array("mobile_number"	=> $phone_number)));
				if($result->num_rows()>0)
				{
                    $row = $result->row();
					if($this->input->post('otp_for') == 'login')
					{
                        if($row->is_registered == 1)
                        {
                            $this->common_model->update_data(array("table" => "otp_validation", "where" => array("mobile_number" => $phone_number), "data" => array("otp"   => $otp_code,"status" => 0)));
                            $response = array("response_info" => 1, "response_message" =>  "Success", "data" => array("otp_code" => $otp_code, "phone_number" => $phone_number));
                        }
                        else
                        {
                            $response = array("response_info" => 0, "response_message" =>  "Registration not completed yet");
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
                                                                "otp"           => $otp_code,
                                                                "status"        => 0,
                                                                "created_at"    => date("Y-m-d H:i:s")
                                                            )
                                    );
                            $insert_id = $this->common_model->insert_data($array);
                            if($insert_id)
                            {
                                $response = array("response_info" => 1, "response_message" =>  "Success", "data" => array("otp_code" =>$otp_code, "phone_number" => $this->input->post('phone_number')));
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
																"created_at"	=> date("Y-m-d H:i:s")
															)
									);
						$insert_id = $this->common_model->insert_data($array);
						if($insert_id)
						{
							$response = array("response_info" => 1, "response_message" =>  "Success", "data" => array("otp_code" =>$otp_code, "phone_number" => $this->input->post('phone_number')));
						}
						else
						{
							$response = array("response_info" => 0, "response_message" =>  "Somthing went wrong please try again...");
						}
					}
					else
					{
						$response = array("response_info" => 0, "response_message" =>  "mobile number not available in our records");
					}
				}
			}
			echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
		}
		public function validateOtpSample()
		{
			$this->form_validation->set_rules('otp_code', 'OTP', 'required|min_length[6]|max_length[6]');
			$this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[10]|integer');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                $phone_number = substr(preg_replace('/[^0-9\-]/', '', $this->input->post('phone_number')), -PHONE_NUMBER_LENGTH);
            	$array = array(
            						"fields"			=> "status, user_email, user_password",
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
            			$response = array(
                                            "response_info"     => 1, 
                                            "response_message"  =>  "Otp is successfully verified", 
                                            "data"              => array("email_id"   => $row->user_email, "password" => $row->user_password)
                                        );
            		    $array['data']	= array("status" => 1);
            		    $this->common_model->update_data($array);
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
            $this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[10]|integer');
			$this->form_validation->set_rules('contacts_list', 'Contacts Array', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                $phone_number = substr(preg_replace('/[^0-9\-]/', '', $this->input->post('phone_number')), -PHONE_NUMBER_LENGTH);
            	$contacts_list = json_decode($this->input->post('contacts_list'));
            	foreach($contacts_list as $contact)
            	{
            		if(strlen($contact->PhoneNumber) >= PHONE_NUMBER_LENGTH)
            			$contacts_numbers[] = substr(preg_replace('/[^0-9\-]/', '', $contact->PhoneNumber), -PHONE_NUMBER_LENGTH);
            	}
                $contacts = implode(',', $contacts_numbers);
            	$app_users = $this->Service_model->get_app_users($contacts);
                foreach ($app_users as $user)
            	{
            		$app_users_data[] = substr(preg_replace('/[^0-9\-]/', '', $user->mobile_number), -PHONE_NUMBER_LENGTH);
            	}
            	$invited_users = $this->Service_model->get_invited_users($contacts, $phone_number);
            	foreach ($invited_users as $user)
            	{
            		$invited_users_data[] = substr(preg_replace('/[^0-9\-]/', '', $user->mobile_number), -PHONE_NUMBER_LENGTH);
            	}
            	$data = array();
            	foreach($contacts_list as $contact)
            	{
                    if($phone_number != substr(preg_replace('/[^0-9\-]/', '', $contact->PhoneNumber),-PHONE_NUMBER_LENGTH))
                    {
                        if(in_array(substr(preg_replace('/[^0-9\-]/', '', $contact->PhoneNumber),-PHONE_NUMBER_LENGTH), $app_users_data))
                        {
                            $array = array("IsInvit" => 0, "IsVioletUser" => 1, "PhoneNumber" => $contact->PhoneNumber, "fullName" => $contact->fullName);
                        }
                        else if(in_array(substr(preg_replace('/[^0-9\-]/', '', $contact->PhoneNumber),-PHONE_NUMBER_LENGTH), $invited_users_data))
                        {
                            $array = array("IsInvit" => 1, "IsVioletUser" => 0, "PhoneNumber" => $contact->PhoneNumber, "fullName" => $contact->fullName);
                        }
                        else
                        {
                            $array = array("IsInvit" => 0, "IsVioletUser" => 0, "PhoneNumber" => $contact->PhoneNumber, "fullName" => $contact->fullName);
                        }
                        array_push($data, $array);
                    }
            	}
            	$response = array("response_info" => 1, "response_message" =>  "Success", "data" => $data);
            	echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
            }
		}
		public function userRegistered()
		{
            $this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[10]|integer');
            $this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('email_id', 'Email id', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                $phone_number = substr(preg_replace('/[^0-9\-]/', '', $this->input->post('phone_number')), -PHONE_NUMBER_LENGTH);
                $array = array("table" => "otp_validation", "data" => array("is_registered" => 1,"user_email" => $this->input->post('email_id'), "user_password" => $this->input->post('password')), "where" => array("mobile_number" => $phone_number));
              	$this->common_model->update_data($array);
              	$array = array("table" => "invited_numbers", "where" => array("mobile_number" => $phone_number));
              	$this->common_model->delete_data($array);
                $response = array("response_info" => 1, "response_message" =>  "Success");
            }
              echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
		}
        public function inviteUser()
        {
            $this->form_validation->set_rules('phone_number', 'Mobile number', 'required|min_length[10]|integer');
            $this->form_validation->set_rules('invited_by', 'Invited by', 'required|min_length[10]|integer');
            if ($this->form_validation->run() == FALSE)
            {
                $response = array("response_info" => 0, "response_message" =>  validation_errors());
            }
            else
            {
                $phone_number = substr(preg_replace('/[^0-9\-]/', '', $this->input->post('phone_number')), -PHONE_NUMBER_LENGTH);
                $array = array(   
                                "table" => "invited_numbers", 
                                "data" => array(
                                                    "mobile_number"     => $phone_number,
                                                    "invited_by"        => substr(preg_replace('/[^0-9\-]/', '', $this->input->post('invited_by')), -PHONE_NUMBER_LENGTH),
                                                    "invited_at"        => date("Y-m-d H:i:s")
                                                )
                               );
                $insert_id = $this->common_model->insert_data($array);
                if($insert_id)
                {
                    $data = array(
                                    "PhoneNumber"   => $this->input->post('phone_number'), 
                                    "invited_by"    => $this->input->post('invited_by'),
                                    "IsInvit"       => 1,
                                    "IsVioletUser"  => 0,
                                    "fullName"      => $this->input->post('fullName')
                                );
                    $response = array("response_info" => 1, "response_message" =>  "Success", "data" => $data);
                }
                else
                {
                      $response = array("response_info" => 0, "response_message" =>  "Failed");
                }
            }
            echo json_encode(array("response" => $response), JSON_UNESCAPED_SLASHES);
        }
	}
?>