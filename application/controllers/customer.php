<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('my_helper');
	    no_cache();

	}

	

	public function dashboard(){
		$data['title']="Home | ".PRODUCT_NAME;    
       		$page='customer-pages/dashboard';
		$this->load_templates($page,$data);
	}


	public function load_templates($page='',$data=''){
		if($this->session_check()==true) {
			$this->load->view('admin-templates/header',$data);
			$this->load->view('admin-templates/nav');
			$this->load->view($page,$data);
			$this->load->view('admin-templates/footer');
			}
		else{
				$this->notAuthorized();
		}
	}

	public function session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==CUSTOMER)) {
			return true;
		} else {
			return false;
		}
	}   


}
