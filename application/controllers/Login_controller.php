<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_controller extends CI_Controller 
{
	public function __construct()
	{
		parent:: __construct();
		$this->load->model('login_model');
		if($this->session->userdata('is_login') != '')
		{
			if($this->uri->segment(1) != 'change-password')
				redirect(base_url('dashboard'));
		}
	}
	public function index()
	{
		$array = array();
		$this->load->view("login");
	}
	public function login()
	{
		if(!$this->input->post())
		{
			redirect('/');die();
		}
		if($this->input->post('email') == '' || $this->input->post('password') == '')
		{
			echo json_encode(array('error' => true ,"message" => "Invalid username or password"));die();
		}
		$data = $this->login_model->login($this->input->post());
		if(!empty($data))
		{
			if($data->id == 1 && $data->status == 1)
			{
				$array = array('is_login' => true, "id" => $data->id);
				$this->session->set_userdata($array);
				$response['error'] = false;
			}
			else if($data->status == 0)
			{
				$response['error'] = true;
				$response['message'] = "Account is inactive";
			}
		}
		else
		{
			$response['error'] = true;
			$response['message'] = "Invalid username or password";
		}
		echo json_encode($response);
	}
	public function forgotPassword()
	{
		if($this->input->post())
		{
			if($this->input->post('email') == '')
			{
				echo json_encode(array("error" => true, "message" => "Email is required"));die();
			}
			$array = array(
							"table"		=> "login",
							"fields"	=> 'email,status',
						 	'where' 	=> array("email" => $this->input->post('email'))
						 );
			$data = $this->common_model->get_data($array);
			if($data->num_rows()>0)
			{
				$string = random_string();
				$this->common_model->delete_data(array("table" => 'login','where' => array("email" => $this->input->post('email')),"data" => array("forgot_string"=> $string), 'type' => 'update'));
				$result = $data->row();
				if($result->status == 1)
				{
					$message = "Please <a href='".base_url('password-reset/'.base64_encode($this->input->post('email').'-'.$string))."'> Click here</a> To rest your password";
					$config = Array(
									'protocol' => 'smtp',
									'smtp_host' => 'ssl://smtp.googlemail.com',
									'smtp_port' => 465,
									//'smtp_user' => '',// your mail name
									//'smtp_pass' => '',
									'mailtype'  => 'html', 
									'charset'   => 'iso-8859-1',
									'wordwrap' => TRUE
									);
					$this->load->library('email', $config);
					$this->email->set_newline("\r\n");
					$this->email->from(PROJECT_NAME.'@gmail.com', 'Admin');//your mail address and name
					$this->email->to($result->email); //receiver mail
					$this->email->subject('testing');
					$this->email->message($message);
					$this->email->send(); //sending mail

					echo json_encode(array("error" => false, "message" => "Password reset mail sent. Please check your inbox.", 'testLink' => base_url('password-reset/'.base64_encode($this->input->post('email').'-'.$string))));die();
				}
				else
				{
					echo json_encode(array("error" => true, "message" => "This account is inactive. Please contact Admin"));die();
				}
			}
			else
			{
				echo json_encode(array("error" => true, "message" => "Acount is not found"));die();
			}
		}
	}
	public function resetPassword($value)
	{
		if($value != '')
		{
			$token = explode('-', base64_decode($value));
			$data = $this->common_model->get_data(array("table" => 'login', 'fields' => 'id, status', 'where' => array("email" => $token[0], 'forgot_string' => $token[1])));
			if($data->num_rows()>0)
			{
				if($data->row()->status == 1)
				{
					//$this->common_model->delete_data(array("table" => 'login', 'where' => array("email" => $token[0]), 'data' => array('forgot_string' => null), 'type' => 'update'));
					$this->session->set_userdata('forgot_pass_data',array("email" => $token[0], 'forgot_string' => $token[1]));
					redirect(base_url('change-password'));
				}
				else
				{

				}
			}
			else
			{
				
			}
		}
	}
	public function changePassword()
	{
		if($this->input->post())
		{
			if($this->input->post('old_password') == '' && !is_array($this->session->userdata('forgot_pass_data')))
			{
				echo json_encode(array("error" => true, "message" => "Current password is required"));die();
			}
			if($this->input->post('new_password') != $this->input->post('re_enter_password'))
			{
				echo json_encode(array("error" => true, "message" => "Password's are not match"));die();
			}
			if($this->input->post('old_password') != '')
			{
				$array = array("table" => 'login',
								"where"=> array("id" => $this->session->userdata('id'), 'password' => $this->input->post('old_password')),
								"data" => array("password" => $this->input->post('new_password')),
								"type" => 'update');
				if($this->common_model->delete_data($array))
					{echo json_encode(array("error" => false, "message" => "Password successfully changed"));die();}
				else
					{echo json_encode(array("error" => true, "message" => "In correct password"));die();}
			}
			if(is_array($this->session->userdata('forgot_pass_data')))
			{
				$session_data = $this->session->userdata('forgot_pass_data');
				$array = array("table" => 'login',
								"where"=> array("email" => $session_data['email'], 'forgot_string' => $session_data['forgot_string']),
								"data" => array("password" => $this->input->post('new_password')),
								"type" => 'update');
				if($this->common_model->delete_data($array))
				{
					echo json_encode(array("error" => false, "message" => "Password successfully changed"));die();
				}
				else
				{
					echo json_encode(array("error" => true, "message" => "oops somthing went wrong. Please try again..."));die();
				}
			}
		}
		else
		{
			$array = array("view" => 'changePassword');
			templetView($array);
		}
	}
}
?>