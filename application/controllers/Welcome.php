<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function index($name='user1')
	{
		$data['name'] = $name;
		echo 3; exit();
		$this->load->view('welcome_message',$data);
	}
}
