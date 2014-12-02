<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('user_model');
		$this->load->model('driver_model');
		$this->load->model('customers_model');
		$this->load->model('trip_booking_model');
		$this->load->model('customers_model');
		$this->load->model('tarrif_model');
		$this->load->model('device_model');
		$this->load->model('vehicle_model');
		no_cache();

	}

	public function index()
	{
		$param1=$this->uri->segment(2);
		$param2=$this->uri->segment(3);
		$param3=$this->uri->segment(4);
		if($this->session_check()==true) {
			if($param1=='' || $param1 == 'home'){
				$this->Dashboard();
			}else if($param1 == 'profile'){
				$this->MyProfile();
			}else if($param1 == 'trip-booking'){
				$this->ShowBookTrip($param2);
			}else{
				$this->notAuthorized();
			}
		}else{
			$this->notAuthorized();
		}
	}

	

	public function Dashboard(){
		$data['title']="Home | ".PRODUCT_NAME;    
       		$page='customer-pages/dashboard';
		$this->load_templates($page,$data);
	}

	//customer profile -> organisaion user customer page(controller->user->Customer())
	public function MyProfile(){
		if($this->session_check()==true) {

			$customer = $this->session->userdata('customer');

			$data['mode']=$customer->id;

			
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

	//new trip booking -> organisaion user new trip(controller->user->ShowBookTrip)
	public function ShowBookTrip($trip_id =''){

		if($this->session_check()==true) {
	
			$tbl_arry=array('booking_sources','available_drivers','available_vehicles','trip_models','vehicle_types','vehicle_models','vehicle_makes','vehicle_ac_types','vehicle_fuel_types','vehicle_seating_capacity','vehicle_beacon_light_options','languages','payment_type','customer_types','customer_groups');
	
			for ($i=0;$i<count($tbl_arry);$i++){
				$result=$this->user_model->getArray($tbl_arry[$i]);
				if($result!=false){
					$data[$tbl_arry[$i]]=$result;
				}
				else{
					$data[$tbl_arry[$i]]='';
				}
			}//echo date('Y-m-d H:i');
			$conditon =array('trip_status_id'=>TRIP_STATUS_PENDING,'CONCAT(pick_up_date," ",pick_up_time) >='=>date('Y-m-d H:i'),'organisation_id'=>$this->session->userdata('organisation_id'));
			$orderby = ' CONCAT(pick_up_date,pick_up_time) ASC';
			$data['notification']=$this->trip_booking_model->getDetails($conditon,$orderby);
			$data['customers_array']=$this->customers_model->getArray();
			$data['tariffs']='';

			if($trip_id!='' && $trip_id > 0) {
				$conditon = array('id'=>$trip_id,'organisation_id'=>$this->session->userdata('organisation_id'));
				$result=$this->trip_booking_model->getDetails($conditon);
				$result=$result[0];
				if($result->trip_status_id==TRIP_STATUS_PENDING || $result->trip_status_id==TRIP_STATUS_CONFIRMED){
		
				$data1['trip_id']=$result->id;
				$data1['recurrent_continues']='';
				$data1['recurrent_alternatives']='';
				if(isset($result->customer_group_id) && $result->customer_group_id > 0){
					$data1['advanced']=TRUE;
					$data1['customer_group']=$result->customer_group_id;
				}else{
					$data1['advanced']='';
					$data1['customer_group']='';
				}

	
				if(isset($result->guest_id) && $result->guest_id > 0){
					$dbdata=array('id'=>$result->guest_id);
					$guest 	=	$this->customers_model->getCustomerDetails($dbdata);
					$guest 	=$guest[0];
					$data1['guest_id']	= $result->guest_id;
					$data1['guest']	=	TRUE;
					$data1['guestname']=	$guest['name'];
					$data1['guestemail']=$guest['email'];
					$data1['guestmobile']=$guest['mobile'];
				}else{
					$data1['guest']='';
					$data1['guestname']='';
					$data1['guestemail']='';
					$data1['guestmobile']='';
				}
			

				$dbdata = array('id'=>$result->customer_id);	
				$customer 	=	$this->customers_model->getCustomerDetails($dbdata);
				if(count($customer)>0){
					$customer=$customer[0];
					$data1['customer']		=	$customer['name'];
					$data1['new_customer']		=	'false';
					$data1['email']					=	$customer['email'];
					$data1['mobile']				=	$customer['mobile'];
	
					$this->session->set_userdata('customer_id',$result->customer_id);
					$this->session->set_userdata('customer_name',$customer['name']);
					$this->session->set_userdata('customer_email',$customer['email']);
					$this->session->set_userdata('customer_mobile',$customer['mobile']);
				}else{
	
					$data1['customer']			=	'';
					$data1['new_customer']			=	'true';
					$data1['email']				=	'';
					$data1['mobile']			=	'';

				}
				$data1['booking_source']		=	$result->booking_source_id;	
				$data1['source']			=	$result->source;
				$data1['trip_model']			=	$result->trip_model_id;
				$data1['no_of_passengers']		=	$result->no_of_passengers;
				$data1['pickupcity']			=	$result->pick_up_city;
				$data1['pickupcitylat']			=	$result->pick_up_lat;
				$data1['pickupcitylng']			=	$result->pick_up_lng;
				$data1['pickuparea']			=	$result->pick_up_area;
				$data1['pickuplandmark']		=	$result->pick_up_landmark;
				$data1['viacity']			=	$result->via_city;
				$data1['viacitylat']			=	$result->via_lat;
				$data1['viacitylng']			=	$result->via_lng;
				$data1['viaarea']			=	$result->via_area;
				$data1['vialandmark']			=	$result->via_landmark;
				$data1['dropdownlocation']		=	$result->drop_city;
				$data1['dropdownlocationlat']		=	$result->drop_lat;
				$data1['dropdownlocationlng']		=	$result->drop_lng;
				$data1['dropdownarea']			=	$result->drop_area;
				$data1['dropdownlandmark']		=	$result->drop_landmark;
				$data1['pickupdatepicker']		=	$result->pick_up_date;
				$data1['dropdatepicker']		=	$result->drop_date;
				$data1['pickuptimepicker']		=	$result->pick_up_time;
				$data1['droptimepicker']		=	$result->drop_time;
				$pickupdatetime			= $result->pick_up_date.' '.$result->pick_up_time;
				$dropdatetime			= $result->drop_date.' '.$result->drop_time;
				$data1['vehicle_type']			=	$result->vehicle_type_id;
				$data1['vehicle_ac_type']		=	$result->vehicle_ac_type_id;
				$data1['vehicle_make']			=	$result->vehicle_make_id;
				$data1['vehicle_model']			=	$result->vehicle_model_id;
				$data1['remarks']			=	$result->remarks;
				$data1['recurrent_yes']			= 	'';
				if(isset($result->vehicle_beacon_light_option_id) && $result->vehicle_beacon_light_option_id > 0){
					$data1['beacon_light']=TRUE;
					if($result->vehicle_beacon_light_option_id==BEACON_LIGHT_RED){

						$data1['beacon_light_radio']='red';
					
					}else{
	
						$data1['beacon_light_radio']='blue';
			
					}
				}else{

					$data1['beacon_light']='';
					$data1['beacon_light_radio']='';
					$data1['beacon_light_id'] = '';

				}
				if(isset($result->pluckcard) && $result->pluckcard==true){
					$data1['pluck_card']=TRUE;
				}else{
					$data1['pluck_card']='';
				}
				if(isset($result->uniform) && $result->uniform==true){
					$data1['uniform']=TRUE;
				}else{
					$data1['uniform']='';
				}
					$data1['seating_capacity']		=	$result->vehicle_seating_capacity_id;
					$data1['language']				=	$result->driver_language_id;
					$data1['tariff']				=	$result->tariff_id;
					$data1['available_vehicle']		=	$result->vehicle_id;
					$data1['available_driver']		=	$result->driver_id;
					$this->session->set_userdata('driver_id',$result->driver_id);
					$data1['customer_type']			=	$result->customer_type_id;
				}else{

					redirect(base_url().'organization/front-desk/trips');
				}
			}
	

			if(isset($data1) && count($data1)>0){
				$data['information']=$data1;
			}else{
				$data['information']=false;
			}
	
			$data['title']="Trip Booking | ".PRODUCT_NAME;  
			$page='user-pages/trip-booking';
			$this->load_templates($page,$data);
	
		}
		else{
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
