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

	public function Profile(){
		if($this->session_check()==true) {

			$customer = $this->session->userdata('customer');

			$data['mode']=$customer->id;

			$this->load->model('user_model');
	    		$this->load->model('trip_booking_model');

			
		
			$pagedata['id']=$customer->id;
			$pagedata['name']=$customer->name;
			$pagedata['email']=$customer->email;
			$pagedata['dob']=$customer->dob;
			$pagedata['mobile']=$customer->mobile;
			$pagedata['address']=$customer->address;
			$pagedata['customer_group_id']=$customer->customer_group_id;
			$pagedata['customer_type_id']=$customer->customer_type_id;
			$pagedata['username']=$this->session->userdata('username');
			
			$tbl_arry=array('customer_types','customer_groups');
			
			for ($i=0;$i<count($tbl_arry);$i++){
				$result=$this->user_model->getArray($tbl_arry[$i]);
				if($result!=false){
					$data[$tbl_arry[$i]]=$result;
				}
				else{
					$data[$tbl_arry[$i]]='';
				}
			} 
			$data['title']=$customer->name." | ".PRODUCT_NAME;
			if(isset($pagedata)){ 
				$data['values']=$pagedata;
			}else{
				$data['values']=false;
			}
			

			//tab settings 
			
			$tdate=date('Y-m-d');
			$date=explode("-",$tdate);
			$fdate='2014-'.$date[1].'-01';
			$todate='2014-'.$date[1].'-31';
			if((isset($_REQUEST['from_pick_date'])|| isset($_REQUEST['to_pick_date']))&& isset($_REQUEST['cdate_search'])){ 
				if($_REQUEST['from_pick_date']==null && $_REQUEST['to_pick_date']==null){
					$fdate='2014-'.$date[1].'-01';
					$todate='2014-'.$date[1].'-31';
				} else{
					$fdate=$_REQUEST['from_pick_date'];
					$todate=$_REQUEST['to_pick_date']; 
				}
				$data['trip_tab']='active';
			
			}
			$data['trips']=$this->trip_booking_model->getCustomerVouchers($customer->id,$fdate,$todate);
			
			$data['cust_tab']='active';
			$data['c_id']=$customer->id;


			$page='user-pages/customer';
		    	$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}

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
