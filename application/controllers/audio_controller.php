<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audio_controller extends CI_Controller 
{
	public function __construct()
	{
		parent:: __construct();
		error_reporting(0);
		if($this->session->userdata('is_login') == '' || $this->session->userdata('is_login') != 'Admin')
		{
			$this->session->set_userdata('redirect_url', $this->uri->segment(1));
			redirect(base_url());
		}
		else
		{
			if(is_array($this->session->userdata('forgot_pass_data')))
			{
				redirect(base_url('change-password'));die();
			}
			$redirect_url = $this->session->userdata('redirect_url');
			if($this->session->userdata('redirect_url') != '')
			{
				$this->session->unset_userdata('redirect_url');
				redirect(base_url($redirect_url));die();
			}
		}
	}
	public function index()
	{
		if($this->input->post("album_name") != '')
		{
			$this->form_validation->set_rules('album_name', 'first_name', 'required');
			$this->form_validation->set_rules('price', 'last_name', 'required');
			$this->form_validation->set_rules('album_id', 'Password', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("error" => true, 'message' => validation_errors());
            }
            else
            {
            	$album_name = $this->input->post("album_name");
            	$price 		= $this->input->post("price");
            	$album_id 	= $this->input->post("album_id");
            	if($album_id > 0)
            	{
            		$array = array(
            			"table"		=> "cv_albums",
            			"data"		=> array("album_name" => $album_name, "price" => $price),
            			"where"		=> array("id" => $album_id)
            		);
            		$this->common_model->update_data($array);
            		$response = array("error" => false, 'message' => "Album details updated.");
            	}
            	else
            	{
            		$array = array(
            			"table"		=> "cv_albums",
            			"data"		=> array("album_name" => $album_name, "price" => $price, "created_at" => date("Y-m-d"), "status" => 1, "type" => 1),
            			"where"		=> array("id" => $album_id)
            		);
            		$this->common_model->insert_data($array);
            		$response = array("error" => false, 'message' => "Album created successfully.");
            	}
            }
            jsonEncode($response);
		}
		else
		{
			$array = array(
				"table" 	=> "cv_albums", 
				'fields' 	=> "id, album_name, created_at, status, price", 
				"where"		=> array("type" => 1),
				"view" 		=> 'audio'
			);
			$array['cv_albums'] = $this->common_model->get_data($array);
			templetView($array);
		}
	}
	public function createAlbum()
	{
		if($this->input->post())
		{
			if($this->input->post('page_title') == '')
			{
				echo json_encode(array("error" => true, "message" => "Page name is required"));die();
			}
			$array = array(
							"table"		=> "cms_pages",
						 	'data' => array(	"page_name" => $this->input->post('page_title'), 
						 						"page_content" => $this->input->post('page_content'), 
						 						"created_at" => date('Y-m-d H:i:s')
						 					)
						 	);
			if($this->input->post('id') != '')
			{
				$array['where'] = array("id" => $this->input->post('id'));
				if($this->common_model->update_data($array))
				{
					echo json_encode(array("error" => false, "message" => "Page updated successfully", "id" => $insert_id));die();
				}
				else
				{
					echo json_encode(array("error" => true, "message" => "Un error occured try again..."));die();
				}
			}
			else
			{
				$insert_id = $this->common_model->insert_data($array);
				if($insert_id)
				{
					echo json_encode(array("error" => false, "message" => "Page created successfully", "id" => $insert_id));die();
				}
				else
				{
					echo json_encode(array("error" => true, "message" => "Un error occured try again..."));die();
				}
			}
		}
		else
		{
			$array = array("view" => 'createCms', 'activeMenu' => 'cms-pages');
			$array['prefix'] = "Create";
			templetView($array);
		}
	}
	public function deleteAlbum($id)
	{
		if(is_numeric($id))
		{
			$array = array(
								"table" => "cms_pages",
								"where" => array("id" => $id),
								'type'	=> 'update',
								"data"	=> array("status" => 0)
							);
			if($this->common_model->delete_data($array))
			{
				echo json_encode(array("error" => false, "message" => "Page successfully deleted"));die();
			}
			else
			{
				echo json_encode(array("error" => true, "message" => "Un error occured try again..."));die();
			}
		}
		else
		{
			echo json_encode(array("error" => true, "message" => "Invalid id"));die();
		}
	}
	public function updateAlbum($id)
	{
		if(is_numeric($id))
		{
			$array = array("table" => 'cms_pages', 'fields' => 'id, page_name, page_content', "where" => array("id" => $id), "view" => 'createCms', 'activeMenu' => 'cms-pages');
			$array['data'] = $this->common_model->get_data($array);
			$array['prefix'] = "Update";
			templetView($array);
		}
		else
		{
			redirect('cms-pages');
		}
	}
}
?>