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
		if(!empty($this->input->post()))
		{
			$this->form_validation->set_rules('album_name', 'Album Name', 'required');
			$this->form_validation->set_rules('price', 'Price', 'required');
			$this->form_validation->set_rules('album_id', 'Album Id', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("error" => true, 'message' => validation_errors());
            }
            else
            {
            	if($_FILES['album_cover']['name'] != '')
            	{
            		$uploadDir	=	FCPATH.IMAGE_ALBUM_PATH;
					if(!is_dir($uploadDir))
					{
						mkdir($uploadDir,'0755', true);
					}
					$config['upload_path']		=	$uploadDir;
					$config['allowed_types']	= 	'gif|jpg|png';
					$config['max_size']			=	'1000';
					$file_name 					=   time("now").str_replace(" ", "_", $_FILES['album_cover']['name']);
					$config['file_name'] 		= 	$file_name;
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload("album_cover"))
					{
						//print_r($this->upload->display_errors());
					}
            	}
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
            		if($file_name != "")
            		{
            			$array['data']['album_cover'] = $file_name;
            		}
            		$this->common_model->update_data($array);
            		$response = array("error" => false, 'message' => "Album details updated.");
            	}
            	else
            	{
            		$array = array(
            			"table"		=> "cv_albums",
            			"data"		=> array("album_name" => $album_name, "price" => $price, "created_at" => date("Y-m-d"), "status" => 1, "type" => 3)
            		);
            		if($file_name != "")
            		{
            			$array['data']['album_cover'] = $file_name;
            		}
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
				'fields' 	=> "id, album_name, album_cover, created_at, status, price", 
				"where"		=> array("type" => 3),
				"view" 		=> 'image'
			);
			$array['cv_albums'] = $this->common_model->get_data($array);
			templetView($array);
		}
	}
	public function manageImageAlbum($id=null)
	{
		if(!empty($this->input->post()))
		{
			$this->form_validation->set_rules('song_name', 'Song Name', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("error" => true, 'message' => validation_errors());
            }
            else
            {
            	if($_FILES['song_file']['name'] != '')
            	{
            		$path = IMAGE_ALBUM_PATH."/".$id;
            		$uploadDir	=	FCPATH.$path;
					if(!is_dir($uploadDir))
					{
						mkdir($uploadDir,'0755', true);
					}
					$config['upload_path']		=	$uploadDir;
					$config['allowed_types']	= 	'gif|jpg|png';
					$config['max_size']			=	'10000';
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload("song_file"))
					{
						$response = array("error" => true, 'message' => $this->upload->display_errors());
						jsonEncode($response);die();
					}
					else
					{
						$file_name = $this->upload->data()['file_name'];
					}
            	}
            	$album_name = $this->input->post("song_name");
            	$album_id 	= $this->input->post("song_id");
            	if($album_id > 0)
            	{
            		$array = array(
            			"table"		=> "cv_image",
            			"data"		=> array("image_name" => $album_name),
            			"where"		=> array("id" => $album_id)
            		);
            		$this->common_model->update_data($array);
            		$response = array("error" => false, 'message' => "Album details updated.");
            	}
            	else
            	{
            		$array = array(
            			"table"		=> "cv_image",
            			"data"		=> array("album_id" => $id, "image_name" => $album_name, "path" => $path."/".$file_name, "created_at" => date("Y-m-d"), "status" => 1)
            		);
            		$this->common_model->insert_data($array);
            		$response = array("error" => false, 'message' => "Album created successfully.");
            	}
            }
            jsonEncode($response);
		}
		else
		{
			$cv_album = array(
				"table" 	=> "cv_albums", 
				'fields' 	=> "id, album_name, album_cover, created_at, status, price", 
				"where"		=> array("type" => 3, "id" => $id),
				"view" 		=> 'audio'
			);
			$array['cv_album'] = $this->common_model->get_data($cv_album)->row();
			$cv_items = array(
				"table" 	=> "cv_image", 
				'fields' 	=> "id, image_name, path, created_at, status", 
				"where"		=> array("album_id" => $id),
				"view" 		=> 'audio'
			);
			$array['cv_items'] = $this->common_model->get_data($cv_items);
			$array['view'] = 'manage-image-album';
			templetView($array);	
		}
	}
	public function removeFile()
	{
		if(!empty($this->input->post()))
		{
			$this->form_validation->set_rules('path', 'File Path', 'required');
			$this->form_validation->set_rules('song_id', 'Id', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $response = array("error" => true, 'message' => validation_errors());
            }
            else
            {
            	$path = $this->input->post("path");
            	$id = $this->input->post("song_id");
            	$array = array("table" => "cv_image", "where" => array("id" => $id));
				if($this->common_model->delete_data($array))
				{
					$response = array("error" => false, 'message' => "File deleted successfully.");
					if(file_exists(FCPATH.$path))
						unlink(FCPATH.$path);
				}
				else
				{
					$response = array("error" => true, "message" => "Un error occured try again...");
				}
			}
			jsonEncode($response);
        }
	}
}
?>