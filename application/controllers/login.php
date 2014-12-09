<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//customer login controller
class Login extends CI_Controller {
	
	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('my_helper');
	    $this->load->model('login_model');
	    no_cache();

	}
	
	public function index($param1 ='',$param2='',$param3 ='',$param4='')
	{
		if($param1=='login' && $param2=='' && $param3==''){

			$this->checking_credentials();

		 }elseif($param1=='admin' && $param2=='' && $param3==''){
			$this->admin();
		 }elseif($param1=='admin' && $param2=='profile' && $param3==''){
			$this->redirect_to_profile();
		 }elseif(($param1=='admin' || $param1=='front-desk' ) && $param2=='changepassword' && $param3==''){ 
			$this->changepassword();
		 } elseif($param1=='admin'  && $param2=='front-desk' && ($param3!= '' || $param4!= '')){
			$this->front_desk($param3,$param4);
		 }else{
			$this->notFound();
		}
	
	}

	public function checking_credentials() 
	{
		if($this->session_check()==true){

			$this->goHome();

		}elseif(isset($_REQUEST['username']) && isset($_REQUEST['password'])){

			 $username = $this->input->post('username');
			 $this->login_model->LoginAttemptsChecks($username);
			 if( $this->session->userdata('isloginAttemptexceeded')==false){
			 	$this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[20]|xss_clean');
			 	$this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[20]|xss_clean');
			 } else {
			 	$captcha = $this->input->post('captcha');
			 	$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_captcha_check');
			 	$this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[20]|xss_clean');
			 	$this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[20]|xss_clean');
			}
			

			 if($this->form_validation->run()!=False){
			 	$username = $this->input->post('username');
		   	 	$pass  = $this->input->post('password');

		     		if( $username && $pass && $this->login_model->Login($username,$pass)) {
				 	if($this->session->userdata('loginAttemptcount') > 1){
		       	 			$this->login_model->clearLoginAttempts($username);
					 }
					$this->goHome();
					
		        
		    		} else {
					if($this->mysession->get('password_error')!='' ){
						$ip_address=$this->input->ip_address();
		        			$this->login_model->recordLoginAttempts($username,$ip_address);
					}
		        		$this->show_login();
		    		}
			} else {

		 	$this->show_login();
			}
		} else {

		 	$this->show_login();
		}
	}


	function goHome(){

		if($this->session->userdata('type')==ORGANISATION_ADMINISTRATOR){
			redirect(base_url().'organization/admin');
		}
		elseif($this->session->userdata('type')==FRONT_DESK){
			redirect(base_url().'organization/front-desk');
		}
		elseif($this->session->userdata('type')==CUSTOMER){
			redirect(base_url().'customer/home');
		}elseif($this->session->userdata('type')==DRIVER){
			redirect(base_url().'driver/home');
		}else{
			$this->notFound();
		}
	}
					

	public function customer_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==CUSTOMER) ) 			{
			return true;
		} else {
			return false;
		}
	}

	public function driver_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==DRIVER)) {
			return true;
		} else {
			return false;
		}
	}

	//check any session exists
	public function session_check() {
		if($this->session->userdata('isLoggedIn')==true ){
			return true;
		} else {
			return false;
		}
	}

	public function show_login() 
	{   	$data['title']="Login | ".PRODUCT_NAME;	
		$this->load->view('access/login',$data);
		
    	}
}
