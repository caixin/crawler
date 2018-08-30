<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Recordinfo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->controllers = substr($this->router->directory,1).$this->router->class;
        $this->load->model('Recordinfo_model', 'recordinfo_db');
    }
	
    public function index()
    {
        redirect("$this->controllers/lists");
    }
	
    public function lists()
    {
        $result = $this->recordinfo_db->result();
        
        $data = array(
            'result' => $result,
        );

        $this->load->view("$this->controllers/lists", $data);
    }
}