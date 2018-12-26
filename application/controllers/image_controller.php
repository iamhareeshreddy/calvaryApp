<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class image_controller extends CI_Controller 
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
		$array = array("view" => 'image');
		templetView($array);
	}
	public function imageAlbums()
	{
		$array = array("table" => "cms_pages", 'fields' => "id, page_name, created_at, status", "view" => 'cmsPages', 'activeMenu' => 'cms-pages');
		$array['data'] = $this->common_model->get_data($array);
		templetView($array);
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