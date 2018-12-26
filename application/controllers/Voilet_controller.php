<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voilet_controller extends CI_Controller 
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
		$array = array("view" => 'dashboard');
		templetView($array);
	}
	public function listFad()
	{
		$this->load->model('voilet_model');
		$data = $this->voilet_model->get_fads();
		$array = array("view" => 'listFad', 'activeMenu' => 'fad', "data" => $data);
		templetView($array);
	}
	public function uploadFad()
	{
		if(count($_FILES) > 0)
		{
			foreach($_FILES as $file)
			{
				$file_name = strtotime(date("Y-m-d H:i:s")).$file['name'];
				if(!move_uploaded_file($file['tmp_name'], './assets/images/fads/'.$file_name))
				{
					echo json_encode(array("error" => true, "message" => "File upload error."));die();
				}
				else
				{
					echo json_encode(array("error" => false, "message" => "File uploaded successfully.", "image_name" => $file_name));die();
				}
			}
		}
	}
	public function createFad()
	{
		if($this->input->post())
		{

			if($this->input->post('fad_name') == '')
			{
				echo json_encode(array("error" => true, "message" => "Category name is required"));die();
			}
			$array = array("table"		=> "fads",
							"data"		=> array(	"fad_name" 			=> $this->input->post('fad_name'), 
													"category" 			=> $this->input->post('category'),
													"fad_description"	=> $this->input->post('fad_description'),
													"fad_image"			=> $this->input->post('image_name'),
													"status"			=> 1,
													"created_at"		=> date('Y-m-d H:i:s')
													));
			if($this->common_model->insert_data($array))
			{
				echo json_encode(array("error" => false, "message" => "FAD successfully created", "data" => $array));die();
			}
			else
			{
				echo json_encode(array("error" => true, "message" => "Somthing went wrong please try again..."));die();
			}
		}
		else
		{
			$array = array("view" => 'createFad', 'activeMenu' => 'fad');
			$where = array(
								"fields"		=> 'id,category_name',
								'table'			=> 'fad_categories',
					);
			$array['categories'] = $this->common_model->get_data($where)->result();
			templetView($array);
		}
	}
	/*public function updateFad()
	{
		if($this->input->post())
		{
			
		}
		else
		{
			
		}
	}*/
	public function addCategory()
	{
		if($this->input->post('category_name') == '')
		{
			echo json_encode(array("error" => true, "message" => "Category name is required"));die();
		}
		$array = array(
						"table"		=> "fad_categories",
					 	'data' => array("category_name" => $this->input->post('category_name'), "category_description" => $this->input->post('category_description'), "created_at" => date('Y-m-d H:i:s')));
		$insert_id = $this->common_model->insert_data($array);
		if($insert_id)
		{
			echo json_encode(array("error" => false, "message" => "Category created successfully", "id" => $insert_id));die();
		}
		else
		{
			echo json_encode(array("error" => true, "message" => "Un error occured try again..."));die();
		}
	}
	public function firebaseSettings()
	{
		$array = array("view" => 'firebaseSettings', 'activeMenu' => 'settings', 'activeSubMenu' => 'firebase-settings');
		$array['table'] = "settings";
		$array['fields'] = "id, settings_value, created_at";
		$array['where'] = array("status" => 1, "settings_key" => 'firebase');
		$data = $this->common_model->get_data($array);
		//echo $this->db->last_query();
		$array['data'] = json_decode($data->row()->settings_value);
		templetView($array);
	}
	public function saveFirebaseKeys()
	{
		if($this->input->post())
		{
			if($this->input->post('api_key') == '')
			{
				echo json_encode(array("error" => true, "message" => "api key is required"));die();
			}
			$array = array(
						"table"		=> "settings",
					 	'data' => array("settings_value" => json_encode(array("api_key" => $this->input->post('api_key'), "key_secret" => $this->input->post('key_secret'), "created_at" => date('Y-m-d H:i:s'), "status" => 1)), "settings_key" => "firebase", "created_at" => date('Y-m-d H:i:s'), "status" => 1));
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
	public function cmsPages()
	{
		$array = array("table" => "cms_pages", 'fields' => "id, page_name, created_at, status", "view" => 'cmsPages', 'activeMenu' => 'cms-pages');
		$array['data'] = $this->common_model->get_data($array);
		templetView($array);
	}
	public function createCmsPage()
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
	public function deleteCmsPage($id)
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
	public function updateCmsPage($id)
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
	public function advert()
	{
		$array = array("view" => 'advert', 'activeMenu' => 'advert');
		$array['table'] = 'adverts';
		$array['fields'] = 'id, advert_name, status, fad_ids, advert_url, created_at';
		//$array['where'] = array("status" => 1);
		$array['data'] = $this->common_model->get_data($array);
		templetView($array);
	}
	public function createAdvert()
	{
		if($this->input->post())
		{
			if($this->input->post('advert_name') == '')
			{
				echo json_encode(array("error" => true, "message" => "Advert Name is required"));die();
			}
			if($this->input->post('fad') == '')
			{
				echo json_encode(array("error" => true, "message" => "Please select atleast one fad"));die();
			}
			if($this->input->post('advert_url') == '')
			{
				echo json_encode(array("error" => true, "message" => "Advert URL is required"));die();
			}
			$array = array(
						"table"		=> "adverts",
					 	'data' 		=> array("advert_name" => $this->input->post('advert_name'), "fad_ids" => implode(',', $this->input->post('fad')), "advert_url" => $this->input->post('advert_url'), "advert_image" => $this->input->post('image_name'), "created_at" => date('Y-m-d H:i:s'), "status" => 1));
			$insert_id = $this->common_model->insert_data($array);
			if($insert_id)
			{
				echo json_encode(array("error" => false, "message" => "Advert created successfully", "id" => $insert_id));die();
			}
			else
			{
				echo json_encode(array("error" => true, "message" => "Un error occured try again..."));die();
			}
		}
		else
		{
			$array = array("view" => 'createAdvert', 'activeMenu' => 'advert');
			$array['table'] = 'fads';
			$array['fields'] = 'id, fad_name';
			$array['where'] = array("status" => 1);
			$array['data'] = $this->common_model->get_data($array);
			templetView($array);
		}
	}
	public function deleteAdvert($id)
	{
		if(is_numeric($id))
		{
			$array = array(
								"table" => "vt_adverts",
								"where" => array("id" => $id),
								'type'	=> 'update',
								"data"	=> array("status" => 0)
							);
			if($this->common_model->delete_data($array))
			{
				echo json_encode(array("error" => false, "message" => "Advert successfully deleted"));die();
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
	public function uploadAdvert()
	{
		if(count($_FILES) > 0)
		{
			foreach($_FILES as $file)
			{
				if(!move_uploaded_file($file['tmp_name'], './assets/images/'.$file['name']))
				{
					echo json_encode(array("error" => true, "message" => "File upload error."));die();
				}
				else
				{
					echo json_encode(array("error" => false, "message" => "File uploaded successfully.", "image_name" => $file['name']));die();
				}
			}
		}
	}
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
?>