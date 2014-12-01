<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

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
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	}   
	public function index(){
		$param1=$this->uri->segment(3);
		$param2=$this->uri->segment(4);
		$param3=$this->uri->segment(5);
        if($this->session_check()==true) {
		if($param1==''){
			$data['title']="Home | ".PRODUCT_NAME;    
       			 $page='user-pages/user_home';
			$this->load_templates($page,$data);
		}elseif($param1=='profile'){
		$this->profile();
		}elseif($param1=='changepassword'){
		$this->changePassword();
		}
		elseif($param1=='settings'){
		$this->settings();
		}elseif($param1=='trip-booking'){

		$this->ShowBookTrip($param2);
		}elseif($param1=='trips'){

		$this->Trips($param2);
		}elseif($param1=='customer'){

		$this->Customer($param2);

		}elseif($param1=='customers'){

		$this->Customers($param2);

		}elseif($param1=='device'){

		$this->Device($param2);

		}elseif($param1=='setup_dashboard'){

		$this->setup_dashboard();

		}elseif($param1=='getNotifications'){
			$this->getNotifications();
		}elseif($param1=='tripvouchers'){
			$this->tripVouchers($param2);
		}

		elseif($param1=='tarrif-masters'&& ($param2== ''|| is_numeric($param2))){
		$this->tarrif_masters($param1,$param2);
		}elseif($param1=='tarrif'&& ($param2== ''|| is_numeric($param2))){
		$this->tarrif($param1,$param2);

		}
		elseif($param1=='driver'){

		$this->ShowDriverView($param2);
		}elseif($param1=='list-driver'&&($param2== ''|| is_numeric($param2))){
		$this->ShowDriverList($param1,$param2);
		}elseif($param1=='driver-profile'&&($param2== ''|| is_numeric($param2))){
		$this->ShowDriverProfile($param1,$param2);
		}
		elseif($param1=='vehicle' && ($param2!= ''|| is_numeric($param2)||$param2== '') &&($param3== ''|| is_numeric($param3))){

		$this->ShowVehicleView($param1,$param2,$param3);
		}
		
		elseif($param1=='list-vehicle'&&($param2== ''|| is_numeric($param2)) && ($param3== ''|| is_numeric($param3))){
		$this->ShowVehicleList($param1,$param2,$param3);
		}else{
			$this->notFound();
		}
		}else{
			$this->notAuthorized();
		}
	
    }
	
	public function settings() {
	if($this->session_check()==true) {
	$tbl_arry=array('vehicle_ownership_types','vehicle_types','vehicle_ac_types','vehicle_fuel_types','vehicle_seating_capacity','vehicle_beacon_light_options','vehicle_makes','vehicle_driver_bata_percentages','vehicle_permit_types','languages','language_proficiency','driver_type','payment_type','customer_types','customer_groups','customer_registration_types','marital_statuses','bank_account_types','id_proof_types','trip_models','trip_statuses','booking_sources','trip_expense_type','vehicle_models');
	
	for ($i=0;$i<24;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	$data['title']="Settings | ".PRODUCT_NAME;  
	$page='user-pages/settings';
	$this->load_templates($page,$data);
	}
	else{
			$this->notAuthorized();
		}
	}
	public function tarrif_masters($param1,$param2) {
	if($this->session_check()==true) {
	$tbl_arry=array('trip_models','vehicle_makes','vehicle_ac_types','vehicle_types');
	$this->load->model('user_model');
		for ($i=0;$i<4;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	//print_r($result);exit;
	//echo $result['id'];exit;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	
		$condition='';
	    $per_page=10;
	    $like_arry='';
	    $org_id=$this->session->userdata('organisation_id'); 
		$where_arry['organisation_id']=$org_id;
	if(isset($_REQUEST['search'])){
		$title = $this->input->post('search_title');
		$trip_model_id = $this->input->post('search_trip_model');
		$vehicle_ac_type_id = $this->input->post('search_ac_type');
	 if(($title=='')&& ($trip_model_id == -1) && ($vehicle_ac_type_id ==-1)){
	 $this->session->set_userdata('Required','Search with value !');
	 redirect(base_url().'organization/front-desk/tarrif-masters');
		}
		else {
		//show search results
		if($param2=='1' ){
				$param2='0';
			}
	if((isset($_REQUEST['search_title'])|| isset($_REQUEST['search_trip_model'])||isset($_REQUEST['search_ac_type']))&& isset($_REQUEST['search'])){
	if($param2==''){
	$param2='0';
	}
	
	if($_REQUEST['search_title']!=null){
	
	$like_arry=array('title'=> $_REQUEST['search_title']); 
	}
	if($_REQUEST['search_trip_model']>0){
	$where_arry['trip_model_id']=$_REQUEST['search_trip_model'];
	}
	if($_REQUEST['search_ac_type']>0){
	$where_arry['vehicle_ac_type_id']=$_REQUEST['search_ac_type'];
	}
	$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	
	}
	}
	}
	    
		$tbl="tariff_masters";
		if(is_null($this->mysession->get('condition'))){//print_r($where_arry);exit;
		$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
		}
		$baseurl=base_url().'organization/front-desk/tarrif-masters/';
		$uriseg ='4';
		
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		if($param2==''){
		$this->mysession->delete('condition');
		}
		
	$data['values']=$p_res['values'];
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	$data['title']="Tarrif Masters | ".PRODUCT_NAME;  
	$page='user-pages/tarrif_master';
	$this->load_templates($page,$data);
	
	
	}
	else{
			$this->notAuthorized();
		}
	
	}
	public function tarrif($param1,$param2){
	if($this->session_check()==true) {
	$tbl_arry=array('vehicle_models');
	for ($i=0;$i<1;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	$result=$this->user_model->getTarrif_masters();
	if($result!=false){
	$data['masters']=$result;
	}else
	{
	$data['masters']='';
	}	//start
		$condition='';
	    $per_page=10;
	    $org_id=$this->session->userdata('organisation_id');
		$where_arry['organisation_id']=$org_id;
	if(isset($_REQUEST['search'])){
		$fdate = $this->input->post('search_from_date');
		$tdate = $this->input->post('search_to_date');
		//valid date check
		/*if(!$this->date_check($fdate)){
	$this->mysession->set('Err_from_date','Invalid From Date for Tariff Search!');
	}
		if(!$this->date_check($tdate)){
	$this->mysession->set('Err_to_date','Invalid To Date for Tariff Search!');
	}*/
		if($fdate!=''&& $tdate==''){
		$tdate=date('Y-m-d');
		}
	 if(($fdate=='')&& ($tdate =='')){
	 $this->session->set_userdata('Date','Search with value');
	 redirect(base_url().'organization/front-desk/tarrif');
		}
		else {
		//show search results
		
	if((isset($_REQUEST['search_from_date'])|| isset($_REQUEST['search_to_date']))&& isset($_REQUEST['search'])){
	if($param2==''){
	$param2='0';
	} 
	if(($_REQUEST['search_from_date']>= $tdate)){
	$this->session->set_userdata('Date_err','Not a valid search');
	}
	if($_REQUEST['search_from_date']!=null){
	
	$where_arry['from_date >=']=$_REQUEST['search_from_date'];
	}
	if($_REQUEST['search_to_date']!=null){
	$where_arry['to_date <=']= $_REQUEST['search_to_date'];
	}
	/*else{
	$where_arry['to_date <=']= $tdate;
	}*/
	
	$this->mysession->set('condition',array("where"=>$where_arry));
	
	//print_r($where_arry);
	}
	}
	}
	    
		$tbl="tariffs";
		if(is_null($this->mysession->get('condition'))){ 
		$this->mysession->set('condition',array("where"=>$where_arry));
		}
		$baseurl=base_url().'organization/front-desk/tarrif/';
		$uriseg ='4';
		
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		if($param2==''){
		$this->mysession->delete('condition');
		}
		
	$data['values']=$p_res['values'];
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	//end
	//$data['allDetails']=$this->user_model->getAll_tarrifDetails();
	$data['title']="Tarrif| ".PRODUCT_NAME; 
	$page='user-pages/tarrif';
	$this->load_templates($page,$data);
	
	}
	else{
			$this->notAuthorized();
		}
	}

	public function Device($param2){
		if($this->session_check()==true) {
	
		$condition='';
	    $per_page=10;
	    $like_arry='';
		$data['s_imei']='';
		$data['s_sim_no']='';
	$where_arry['organisation_id']=$this->session->userdata('organisation_id');
		
	if((isset($_REQUEST['s_imei']) || isset($_REQUEST['s_sim_no'])) && isset($_REQUEST['search'])){
	if($param2==''){
	$param2=0;
	}
	
	if($_REQUEST['s_imei']!=null){
	$data['s_imei']=$_REQUEST['s_imei'];
	$like_arry['imei']=$_REQUEST['s_imei'];
	}
	if($_REQUEST['s_sim_no']!=null){
	$data['s_sim_no']=$_REQUEST['s_sim_no'];
	$like_arry['sim_no'] = $_REQUEST['s_sim_no'];
	}
	
	$this->mysession->set('condition',array("like"=>$like_arry));
	}
	/*if($this->mysession->get('condition')){
		$this->mysession->set('condition',array("like"=>$like_arry));
	}*/
	
	    
		$tbl="devices";
		if(is_null($this->mysession->get('condition'))){ 
		$this->mysession->set('condition',array("where"=>$where_arry));
		}
		$baseurl=base_url().'organization/front-desk/device/';
		$uriseg ='4';
		
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		if($param2==''){
		$this->mysession->delete('condition');
		}
		
	$data['values']=$p_res['values'];
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	$devices=$this->device_model->getReg_Num();
	if($devices!=false){
	$data['devices']=$devices;
	}else{
	$data['devices']='';
	}
	$data['title']="Device | ".PRODUCT_NAME; 
	$page='user-pages/device';
	$this->load_templates($page,$data);
	
	}
	else{
			$this->notAuthorized();
		}



	}




	public function ShowBookTrip($trip_id =''){
	if($this->session_check()==true) {
	
	//echo $this->session->userdata('organisation_id');
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
	
	$dbdata=array('id'=>$result->customer_id);	
	$customer 	=	$this->customers_model->getCustomerDetails($dbdata);
	if(count($customer)>0){
	$customer=$customer[0];
	$data1['customer']				=	$customer['name'];
	$data1['new_customer']			=	'false';
	$data1['email']					=	$customer['email'];
	$data1['mobile']				=	$customer['mobile'];
	
	$this->session->set_userdata('customer_id',$result->customer_id);
	$this->session->set_userdata('customer_name',$customer['name']);
	$this->session->set_userdata('customer_email',$customer['email']);
	$this->session->set_userdata('customer_mobile',$customer['mobile']);
	}else{
	
	$data1['customer']				=	'';
	$data1['new_customer']			=	'true';
	$data1['email']					=	'';
	$data1['mobile']				=	'';

	}
	$data1['booking_source']			=	$result->booking_source_id;	
	$data1['source']					=	$result->source;
	$data1['trip_model']				=	$result->trip_model_id;
	$data1['no_of_passengers']		=	$result->no_of_passengers;
	$data1['pickupcity']				=	$result->pick_up_city;
	$data1['pickupcitylat']			=	$result->pick_up_lat;
	$data1['pickupcitylng']			=	$result->pick_up_lng;
	$data1['pickuparea']				=	$result->pick_up_area;
	$data1['pickuplandmark']			=	$result->pick_up_landmark;
	$data1['viacity']				=	$result->via_city;
	$data1['viacitylat']				=	$result->via_lat;
	$data1['viacitylng']				=	$result->via_lng;
	$data1['viaarea']				=	$result->via_area;
	$data1['vialandmark']			=	$result->via_landmark;
	$data1['dropdownlocation']		=	$result->drop_city;
	$data1['dropdownlocationlat']	=	$result->drop_lat;
	$data1['dropdownlocationlng']	=	$result->drop_lng;
	$data1['dropdownarea']			=	$result->drop_area;
	$data1['dropdownlandmark']		=	$result->drop_landmark;
	$data1['pickupdatepicker']		=	$result->pick_up_date;
	$data1['dropdatepicker']			=	$result->drop_date;
	$data1['pickuptimepicker']		=	$result->pick_up_time;
	$data1['droptimepicker']			=	$result->drop_time;
	$pickupdatetime			= $result->pick_up_date.' '.$result->pick_up_time;
	$dropdatetime			= $result->drop_date.' '.$result->drop_time;
	$data1['vehicle_type']			=	$result->vehicle_type_id;
	$data1['vehicle_ac_type']		=	$result->vehicle_ac_type_id;
	$data1['vehicle_make']			=	$result->vehicle_make_id;
	$data1['vehicle_model']			=	$result->vehicle_model_id;
	$data1['remarks']				=	$result->remarks;
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
	/*if(isset($data1['vehicle_type']) && isset($data1['vehicle_ac_type']) && isset($data1['vehicle_make']) && isset($data1['vehicle_model']) && isset($pickupdatetime) && isset($dropdatetime)){
	$available=array('vehicle_type'=>$data1['vehicle_type'],'vehicle_ac_type'=>$data1['vehicle_ac_type'],'vehicle_make'=>$data1['vehicle_make'],'vehicle_model'=>$data1['vehicle_model'],'pickupdatetime'=>$pickupdatetime,'dropdatetime'=>$dropdatetime,'organisation_id'=>$this->session->userdata('organisation_id'));
	$res_vehicles=$this->getAvailableVehicle($available);
	
	$res_tariffs=$this->tariffSelecter($available);
	$available_vehicles='';
	$available_tarif='';
	if(count($res_vehicles[0])>0){
	for($index_vehicles=0;$index_vehicles<count($res_vehicles);$index_vehicles++){
		$available_vehicles[$res_vehicles[$index_vehicles]['vehicle_id']]=$res_vehicles[$index_vehicles]['registration_number'];
	}
	}
	for($index_tarif=0;$index_tarif<count($res_tariffs);$index_tarif++){
		$available_tarif[$res_tariffs[$index_tarif]['id']]=$res_tariffs[$index_tarif]['title'];
	}
	$data['tariffs']=$available_tarif;
	$data['available_vehicles']=$available_vehicles;
	}else if(isset($data1['vehicle_type']) && isset($data1['vehicle_ac_type']) && isset($data1['vehicle_make']) && isset($data1['vehicle_model'])){
	$available=array('vehicle_type'=>$data1['vehicle_type'],'vehicle_ac_type'=>$data1['vehicle_ac_type'],'vehicle_make'=>$data1['vehicle_make'],'vehicle_model'=>$data1['vehicle_model'],'organisation_id'=>$this->session->userdata('organisation_id'));
	$res_tariffs=$this->tariffSelecter($available);
	$data['available_vehicles']='';
	}else{
	$data['tariffs']='';
	$data['available_vehicles']='';
	}*/

	if(isset($data1) && count($data1)>0){
	$data['information']=$data1;
	}else{
	$data['information']=false;
	}
	/*$_REQUEST['c_group_val']='';
	if(isset($_REQUEST['c_group_val']) || $_REQUEST['c_group_val']!='' ){
	echo $_REQUEST['c_group_val'];
	}*/
	$data['title']="Trip Booking | ".PRODUCT_NAME;  
	$page='user-pages/trip-booking';
	$this->load_templates($page,$data);
	
	}
	else{
			$this->notAuthorized();
		}
	}

	public function getAvailableVehicle($available){
	
	
	return $this->trip_booking_model->selectAvailableVehicles($available);

	}
	public function tariffSelecter($data){
	
	return $this->tarrif_model->selectAvailableTariff($data);

	

	}
	public function Trips($param2){
		if($this->session_check()==true) {
			$like_arry='';
			$where_arry='';
			$tbl="trips";
			$baseurl=base_url().'organization/front-desk/trips/';
			$per_page=30;
			$data['slno_per_page']=10;
			$uriseg ='4';
			$data['urlseg']=4;
			$tdate=date('Y-m-d');
			$data['trip_pick_date']='';
			$data['trip_drop_date']='';
			$data['vehicles']='';
			$data['cgroups']='';
			$data['drivers']='';
			$data['customer']='';
			$data['trip_status_id']='';
			$qry='SELECT ORG.name as company_name ,VO.name as ownership,T.customer_id,T.customer_group_id,T.remarks,T.vehicle_model_id,T.vehicle_ac_type_id,T.driver_id,T.vehicle_id,T.guest_id,V.vehicle_ownership_types_id,T.tariff_id,T.trip_status_id,T.id as trip_id,T.booking_date,T.drop_date,T.drop_time,T.pick_up_date,T.pick_up_time,VM.name as model,V.registration_number,T.pick_up_city,T.pick_up_area,G.name as guest_name,G.mobile as guest_info,T.drop_city,T.drop_area,C.name as customer_name,C.mobile as customer_mobile,CG.name as customer_group,D.name as driver,D.mobile as driver_info FROM trips T LEFT JOIN vehicle_models VM ON VM.id=T.vehicle_model_id LEFT JOIN vehicles V ON V.id=T.vehicle_id LEFT JOIN customers G ON G.id=T.guest_id LEFT JOIN customers C ON C.id=T.customer_id LEFT JOIN customer_groups CG ON CG.id=T.customer_group_id LEFT JOIN drivers D ON D.id=T.driver_id LEFT JOIN vehicle_ownership_types VO ON V.vehicle_ownership_types_id=VO.id LEFT JOIN organisations ORG ON ORG.id = T.organisation_id where T.organisation_id='.$this->session->userdata('organisation_id');
			if($param2=='1' ){
				$param2='0';
			}
			//$where_arry['organisation_id']=$this->session->userdata('organisation_id');
			//$order_arry="id desc";
			if((isset($_REQUEST['trip_pick_date'])|| isset($_REQUEST['trip_drop_date'])|| isset($_REQUEST['vehicles'])|| isset($_REQUEST['drivers'])|| isset($_REQUEST['trip_status_id'])|| isset($_REQUEST['cgroup'])||isset($_REQUEST['customer']))&& isset($_REQUEST['trip_search'])){
				if($param2==''){
				$param2='0';
				}
				
				if($_REQUEST['trip_pick_date']!=null && $_REQUEST['trip_drop_date']!=null){
					$data['trip_pick_date']=$_REQUEST['trip_pick_date'];
					$data['trip_drop_date']=$_REQUEST['trip_drop_date'];
					$qry.=' AND (T.drop_date BETWEEN "'.$_REQUEST['trip_pick_date'].'" AND "'.$_REQUEST['trip_drop_date'].'")';
					//$qry.=' AND T.pick_up_date BETWEEN "'.$_REQUEST['trip_pick_date'].'" AND "'.$_REQUEST['trip_drop_date'].'" AND T.drop_date BETWEEN "'.$_REQUEST['trip_pick_date'].'" AND "'.$_REQUEST['trip_drop_date'].'"';		
					$where_arry['trip_pick_date']=$_REQUEST['trip_pick_date'];
					$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
				}else if($_REQUEST['trip_pick_date']!=null){
				$data['trip_pick_date']=$_REQUEST['trip_pick_date'];
				$qry.=' AND  (T.pick_up_date="'.$_REQUEST['trip_pick_date'].'" or "'.$_REQUEST['trip_pick_date'].'" BETWEEN T.pick_up_date and T.drop_date)';
				//$qry.=' AND (T.pick_up_date="'.$_REQUEST['trip_pick_date'].'" OR T.drop_date="'.$_REQUEST['trip_pick_date'].'") ';
				$where_arry['trip_pick_date']=$_REQUEST['trip_pick_date'];
				}else if($_REQUEST['trip_drop_date']!=null){
				$data['trip_drop_date']=$_REQUEST['trip_drop_date'];
				$qry.=' AND (T.drop_date ="'.$_REQUEST['trip_drop_date'].'")';
				$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
				}
				if($_REQUEST['vehicles']!=null && $_REQUEST['vehicles']!=gINVALID){
					$data['vehicle_id']= $_REQUEST['vehicles'];
					$qry.=' AND T.vehicle_id ="'.$_REQUEST['vehicles'].'"';
					$where_arry['vehicle_id']=$_REQUEST['vehicles'];
				}
				if($_REQUEST['drivers']!=null && $_REQUEST['drivers']!=gINVALID){
					$data['driver_id']= $_REQUEST['drivers'];
					$qry.=' AND T.driver_id ="'.$_REQUEST['drivers'].'"';
					$where_arry['driver_id']=$_REQUEST['drivers'];
				}
				if($_REQUEST['trip_status_id']!=null && $_REQUEST['trip_status_id']!=gINVALID){
					$data['trip_status_id']= $_REQUEST['trip_status_id'];
					$qry.=' AND T.trip_status_id ="'.$_REQUEST['trip_status_id'].'"';
					$where_arry['trip_status_id']=$_REQUEST['trip_status_id'];
				}
				if($_REQUEST['cgroups']!=null && $_REQUEST['cgroups']!=gINVALID){
					$data['customer_group_id']= $_REQUEST['cgroups'];
					$qry.=' AND T.customer_group_id ="'.$_REQUEST['cgroups'].'"';
					$where_arry['customer_group_id']=$_REQUEST['cgroups'];
				}
				if($_REQUEST['customer']!=null){
					$data['customer_name']= $_REQUEST['customer'];
					$qry.=' And C.name Like "%'.$_REQUEST['customer'].'%"';
					$like_arry['customer_name']=$_REQUEST['customer'];
				}
				if(isset($where_arry)|| isset($like_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
				}
				
			}else if($this->mysession->get('condition')!=''){ 
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['trip_pick_date']) || isset($condition['where']['trip_drop_date'])|| isset($condition['where']['vehicle_id']) || isset($condition['where']['driver_id'])|| isset($condition['where']['trip_status_id'])){
				//print_r($condition);
				/*if(isset($condition['where']['trip_id'])){
				$data['trip_id']=$condition['where']['trip_id'];
				$qry.=' AND T.id ='.$condition['where']['trip_id'];
				}*/
				if($condition['where']['trip_pick_date']!=null || $condition['where']['trip_drop_date']!=null || $condition['where']['vehicle_id']!=null || $condition['where']['driver_id']!=null || $condition['where']['trip_status_id']!=null){
				if(isset($condition['where']['trip_pick_date'])  && isset($condition['where']['trip_drop_date']) ){
				$data['trip_pick_date']=$condition['where']['trip_pick_date'];
				$data['trip_drop_date']=$condition['where']['trip_drop_date'];
				//$qry.=' AND T.pick_up_date >="'.$condition['where']['trip_pick_date'].'" AND T.drop_date <="'.$condition['where']['trip_drop_date'].'"';
				$qry.=' AND T.pick_up_date BETWEEN "'.$condition['where']['trip_pick_date'].'" AND "'.$condition['where']['trip_drop_date'].'" AND T.drop_date BETWEEN "'.$condition['where']['trip_pick_date'].'" AND "'.$condition['where']['trip_drop_date'].'"';
				}else if(isset($condition['where']['trip_pick_date'])){
				$data['trip_pick_date']=$condition['where']['trip_pick_date'];
				//$qry.=' AND T.pick_up_date ="'.$condition['where']['trip_pick_date'].'"';
				$qry.=' AND (T.pick_up_date="'.$condition['where']['trip_pick_date'].'" OR T.drop_date="'.$condition['where']['trip_pick_date'].'") OR ((T.pick_up_date <= "'.$condition['where']['trip_pick_date'].'" AND T.drop_date >= "'.$condition['where']['trip_pick_date'].'"))';
				}else if(isset($condition['where']['trip_drop_date'])){
				$data['trip_drop_date']=$condition['where']['trip_drop_date'];
				$qry.=' AND T.drop_date ="'.$condition['where']['trip_drop_date'].'"';
				

				}
				if(isset($condition['where']['vehicle_id']) && $condition['where']['vehicle_id']!=null){
				$data['vehicle_id']=$condition['where']['vehicle_id'];
				$qry.=' AND T.vehicle_id ="'.$condition['where']['vehicle_id'].'"';
				}
				if(isset($condition['where']['driver_id']) && $condition['where']['driver_id']!=null){
				$data['driver_id']=$condition['where']['driver_id'];
				$qry.=' AND T.driver_id ="'.$condition['where']['driver_id'].'"';
				}
				if(isset($condition['where']['trip_status_id']) && $condition['where']['trip_status_id']!=null){
				$data['trip_status_id']=$condition['where']['trip_status_id'];
				$qry.=' AND T.trip_status_id ="'.$condition['where']['trip_status_id'].'"';
				}
				if(isset($condition['where']['customer_group_id']) && $condition['where']['customer_group_id']!=null){
				$data['customer_group_id']=$condition['where']['customer_group_id'];
				$qry.=' AND T.customer_group_id ="'.$condition['where']['customer_group_id'].'"';
				}
				if(isset($condition['like']['customer_name']) && $condition['like']['customer_name']!=null){
				$data['customer_name']=$condition['like']['customer_name'];
				$qry.=' And C.name Like "%'.$condition['like']['customer_name'].'%"';
				}
				}
			}
			}
			$qry.=' order by T.id desc';
			
			$tbl_arry=array('trip_statuses','customer_groups');
	
			for ($i=0;$i<count($tbl_arry);$i++){
			$result=$this->user_model->getArray($tbl_arry[$i]);
			if($result!=false){
			$data[$tbl_arry[$i]]=$result;
			}
			else{
			$data[$tbl_arry[$i]]='';
			}
			}
			/*if($param2=='1'){
				$param2=0;
			}*/
			//echo $qry;exit;
			$this->load->model('account_model');
			$data['taxes']=$this->account_model->getTaxArray($condition='');
			//print_r($data['taxes']);exit;

			$data['vehicles']=$this->trip_booking_model->getVehiclesArray($condition='');
			$data['drivers']=$this->driver_model->getDriversArray($condition=''); 
			$paginations=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['trips']=$paginations['values'];
			if(empty($data['trips'])){
				$data['result']="No Results Found !";
					}
			//echo '<pre>';print_r($data['trips']);echo '</pre>';exit;
			//$data['trips']=$this->trip_booking_model->getDetails($conditon='');echo '<pre>';print_r($data['trips']);echo '</pre>';exit;
			$data['status_class']=array(TRIP_STATUS_PENDING=>'label-warning',TRIP_STATUS_CONFIRMED=>'label-success',TRIP_STATUS_CANCELLED=>'label-danger',TRIP_STATUS_CUSTOMER_CANCELLED=>'label-danger',TRIP_STATUS_ON_TRIP=>'label-primary',TRIP_STATUS_TRIP_COMPLETED=>'label-success',TRIP_STATUS_TRIP_PAYED=>'label-info',TRIP_STATUS_TRIP_BILLED=>'label-success');
			$data['trip_statuses']=$this->user_model->getArray('trip_statuses'); 
			$data['customers']=$this->customers_model->getArray();
			$data['title']="Trips | ".PRODUCT_NAME;  
			$page='user-pages/trips';
		    $this->load_templates($page,$data);
		    }else{
				$this->notAuthorized();
			}
		
	}	
	
	public function Customer($param2=''){
		if($this->session_check()==true) {
		$data['mode']=$param2;
		
		
			if($param2!=''){
				$condition=array('id'=>$param2);
				$result=$this->customers_model->getCustomerDetails($condition);
				$pagedata['id']=$result[0]['id'];
				$pagedata['name']=$result[0]['name'];
				$pagedata['email']=$result[0]['email'];
				$pagedata['dob']=$result[0]['dob'];
				$pagedata['mobile']=$result[0]['mobile'];
				$pagedata['address']=$result[0]['address'];
				$pagedata['customer_group_id']=$result[0]['customer_group_id'];
				$pagedata['customer_type_id']=$result[0]['customer_type_id'];
			}
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
			$data['title']="Customer | ".PRODUCT_NAME;
			if(isset($pagedata)){ 
				$data['values']=$pagedata;
			}else{
				$data['values']=false;
			}
			if($param2!=''){
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
			$todate=$_REQUEST['to_pick_date']; }
			$data['trip_tab']='active';
			
			}
			$data['trips']=$this->trip_booking_model->getCustomerVouchers($param2,$fdate,$todate);
			}
			$data['cust_tab']='active';
			$data['c_id']=$param2;
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

public function	Customers($param2){
			if($this->session_check()==true) {
				if($this->mysession->get('condition')!=null){
						$condition=$this->mysession->get('condition');
						if(isset($condition['like']['name']) || isset($condition['like']['mobile'])|| isset($condition['where']['customer_type_id']) || isset($condition['where']['customer_group_id'])){
						}
						else{
						$this->mysession->delete('condition');
						}
						}
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
			
			$tbl="customers";
			$baseurl=base_url().'organization/front-desk/customers/';
			$per_page=10;
			$uriseg ='4';
			
			$where_arry['organisation_id']=$this->session->userdata('organisation_id');
			$like_arry['organisation_id']=$this->session->userdata('organisation_id');

			if((isset($_REQUEST['customer'])|| isset($_REQUEST['mobile']) || isset($_REQUEST['customer_type_id']))&& isset($_REQUEST['customer_search'])){	
				
				if($param2==''){
				$param2='0';
				}
				if($_REQUEST['customer']!=null){
					$data['customer']=$_REQUEST['customer'];
					$like_arry['name']=$_REQUEST['customer'];
				}
				if($_REQUEST['mobile']!=null){
					$data['mobile']=$_REQUEST['mobile'];
					$like_arry['mobile']=$_REQUEST['mobile'];
				}
				if($_REQUEST['customer_type_id']!=null && $_REQUEST['customer_type_id']!=gINVALID){
				$data['customer_type_id']=$_REQUEST['customer_type_id'];
				$where_arry['customer_type_id']=$_REQUEST['customer_type_id'];
				}
				if($_REQUEST['customer_group_id']!=null && $_REQUEST['customer_group_id']!=gINVALID){
				$data['customer_group_id']=$_REQUEST['customer_group_id'];
				$where_arry['customer_group_id']=$_REQUEST['customer_group_id'];
				}
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
			}
			if(is_null($this->mysession->get('condition'))){
			$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
			}
						
			$paginations=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['customers']=$paginations['values'];	
				for($i=0;$i<count($data['customers']);$i++){
					$id=$data['customers'][$i]['id'];
					$availability=$this->customers_model->getCurrentStatuses($id);
					if($availability==false){
					$customer_statuses[$id]='NoBookings';
					$customer_trips[$id]=gINVALID;
					}else{
					$customer_statuses[$id]='OnTrip';
					$customer_trips[$id]=$availability[0]['id'];
					}
				}//print_r($customer_statuses);print_r($customer_trips);exit;
				if(isset($customer_statuses) && count($customer_statuses)>0){
				$data['customer_statuses']=$customer_statuses;
				}	
				if(isset($customer_trips) && count($customer_trips)>0){
				$data['customer_trips']=$customer_trips;
				}		
			if(empty($data['customers'])){
				$data['result']="No Results Found !";
				}
			$data['title']="Customers | ".PRODUCT_NAME;  
			$page='user-pages/customers';
		    $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
}
	
public function profile() {
	   if($this->session_check()==true) {
		
		$dbdata = '';
              if(isset($_REQUEST['user-profile-update'])){ 
			$dbdata['first_name'] = $this->input->post('firstname');
			$dbdata['last_name']  = $this->input->post('lastname');
		    $dbdata['email'] 	   = $this->input->post('email');
			$hmail 	   = $this->input->post('hmail');
			$dbdata['phone'] 	   = $this->input->post('phone');
			$hphone 	   = $this->input->post('hphone');
		    $dbdata['address']   = $this->input->post('address');
			$dbdata['username']   = $this->input->post('husername');
			$fadata['firstname'] = $this->input->post('firstname');
			$fadata['lastname']  = $this->input->post('lastname');
		    $fadata['email'] 	   = $this->input->post('email');
			$fadata['phone'] 	   = $this->input->post('phone');
			$fadata['fa_account']   = $this->input->post('fa_account');
			//$this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('firstname','First Name','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('lastname','Last Name','trim|required|min_length[2]|xss_clean');
			if($dbdata['email'] == $hmail){
			$this->form_validation->set_rules('email','Mail','trim|required|valid_email');
		}else{
			$this->form_validation->set_rules('email','Mail','trim|required|valid_email|is_unique[users.email]');
		}
			if($dbdata['phone'] == $hphone){
			$this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean');
		}else{
			$this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean||is_unique[users.phone]');
		}
			
			$this->form_validation->set_rules('address','Address','trim|required|min_length[10]|xss_clean');
			//$dbdata['username']  = $this->input->post('username');
		   	
			
			if($this->form_validation->run() != False) {
				$val    		   = $this->user_model->updateProfile($dbdata);
				if($val==true){
				//fa user edit
					$this->load->model('account_model');
					$this->account_model->edit_user($fadata);
                   
				redirect(base_url().'organization/front-desk');
				}
			}else{
				$this->show_profile($dbdata);
			}
		}else{
			
			$this->show_profile($dbdata);

		}
	   }	
		else{
			$this->notAuthorized();
		}
	}
	public function show_profile($data) {
		  if($this->session_check()==true) {
			if($data == ''){
			$data['values']=$this->user_model->getProfile();
			}else{
			$data['postvalues']=$data;
			}
			$data['title']="Profile | ".PRODUCT_NAME;  
			$page='user-pages/profile';
		    $this->load_templates($page,$data);
		    }
			else{
				$this->notAuthorized();
			}
	}
	public function changePassword() {
	if($this->session_check()==true) {
	   $this->load->model('user_model');
	   $data['old_password'] = 	'';
		$data['password']	  = 	'';
		$data['cpassword'] 	  = 	'';
       if(isset($_REQUEST['user-password-update'])){
			$this->form_validation->set_rules('old_password','Current Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('password','New Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('cpassword','Confirm Password','trim|required|min_length[5]|max_length[12]|matches[password]|xss_clean');
			$data['old_password'] = trim($this->input->post('old_password'));
			$data['password'] = trim($this->input->post('password'));
			$data['cpassword'] = trim($this->input->post('cpassword'));
			if($this->form_validation->run() != False) {
				$dbdata['password']  	= md5($this->input->post('password'));
				$dbdata['old_password'] = md5(trim($this->input->post('old_password')));
				$val    			    = $this->user_model->changePassword($dbdata);
				if($val == true) {				
					redirect(base_url().'organization/front-desk');
				}else{
					$this->show_change_password($data);
				}
			} else {
				
					$this->show_change_password($data);
			}
		} else {
			
					$this->show_change_password($data);
		}
		           }
		else{
			$this->notAuthorized();
		}
	}	
   
	public function show_change_password($data) {
		if($this->session_check()==true) {
				$data['title']="Change Password | ".PRODUCT_NAME;  
				$page='user-pages/change_password';
				 $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	public function ShowDriverView($param2) {
		if($this->session_check()==true) {
				$data['select']=$this->select_Box_Values();
				
				
			//trip details
		
			if($param2!=''){
			
			$data['trips']=$this->trip_booking_model->getDriverVouchers($param2);
			}
			
			//sample ends
				$data['title']="Driver Details | ".PRODUCT_NAME;  
				$page='user-pages/addDrivers';
				 $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	
	public function ShowDriverList($param1,$param2) {
	if($this->session_check()==true) { 
	/*if($this->mysession->get('condition')!=null){
	$condition=$this->mysession->get('condition');
	if(isset($condition['like']['name']) || isset($condition['like']['district']) ){
	}
	else{
	$this->mysession->delete('condition');
	}
	}*/
			$data['driver_name']='';
			$data['driver_city']='';
			$data['status_id']='';
	$condition='';
	$per_page=10;
	$like_arry=''; 
	$org_id=$this->session->userdata('organisation_id');
	$where_arry['organisation_id']=$org_id;
	$qry='SELECT D.id,D.name
FROM drivers D where D.organisation_id = '.$this->session->userdata('organisation_id');
			if($param2=='1' ){
				$param2='0';
				}
	if($param2==''){
	$this->mysession->delete('condition');

	}
	//for search
	   if(isset($_REQUEST['search'])){
	if($param2==''){
	$param2='0';
	}
	if($_REQUEST['status']!=null && $_REQUEST['status']!=-1 ){
	$data['status_id']=$_REQUEST['status'];
	$date_now=date('Y-m-d H:i:s');
	$where_arry['dstatus']=$_REQUEST['status'];
	$qry=' SELECT D.id
			FROM drivers AS D  LEFT JOIN trips as T ON D.id= T.driver_id where D.organisation_id = '.$this->session->userdata('organisation_id').'
			AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
			AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
			AND T.organisation_id = '.$this->session->userdata('organisation_id').'
			AND T.driver_id = D.id
			AND T.trip_status_id = '.TRIP_STATUS_CONFIRMED;
	}
	if($_REQUEST['status']!=null && $_REQUEST['status']==0 ){
	$data['status_id']=$_REQUEST['status'];
	$date_now=date('Y-m-d H:i:s');
	$where_arry['dstatus']=$_REQUEST['status'];
	$qry='SELECT D.id FROM drivers D WHERE  D.organisation_id = "2" AND D.id NOT IN ('.$qry.')';
	}
	if($_REQUEST['driver_name']!=null){
	$data['driver_name']=$_REQUEST['driver_name'];
	$qry.=' AND D.name LIKE "%'.$_REQUEST['driver_name'].'%" ';
	$like_arry['name']=$_REQUEST['driver_name'];
	}
	
	if($_REQUEST['driver_city']!=null){
	$data['driver_city']=$_REQUEST['driver_city'];
	$like_arry['district']=$_REQUEST['driver_city'];
	$qry.=' AND D.district LIKE "%'.$_REQUEST['driver_city'].'%" ';
	}
if(isset($where_arry) || isset($like_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
				}
	//$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	} 
	else if($this->mysession->get('condition')!=''){ 
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['dstatus']) || isset($condition['like']['name']) || isset($condition['like']['district']) ){
				
				if(isset($condition['where']['dstatus']) && $condition['where']['dstatus']!=-1 ){
				$data['status_id']=$condition['where']['dstatus'];
				$date_now=date('Y-m-d H:i:s');
				//$where_arry['status']=$_REQUEST['status'];
			$qry=' SELECT D.id
			FROM drivers AS D  LEFT JOIN trips as T ON D.id= T.driver_id where D.organisation_id = '.$this->session->userdata('organisation_id').'
			AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
			AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
			AND T.organisation_id = '.$this->session->userdata('organisation_id').'
			AND T.driver_id = D.id
			AND T.trip_status_id = '.TRIP_STATUS_CONFIRMED;
				
				}	
				if(isset($condition['where']['dstatus'])&& $condition['where']['dstatus']==0){
				$data['status_id']=$condition['where']['dstatus'];
				$date_now=date('Y-m-d H:i:s');
				//$where_arry['status_id']=$_REQUEST['status'];
				$qry='SELECT D.id FROM drivers D WHERE  D.organisation_id = "2" AND D.id NOT IN ('.$qry.')';
				
				}
				if(isset($condition['like']['name'])){
				
				$data['driver_name']=$condition['like']['name'];
				$qry.=' AND D.name LIKE "%'.$condition['like']['name'].'%" ';
				}
				if(isset($condition['like']['district'])){
				$data['driver_city']=$condition['like']['district'];
				$qry.=' AND D.district LIKE "%'.$condition['like']['district'].'%" ';
				}
				
				
			}
			}
	/*if(is_null($this->mysession->get('condition'))){
	$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	}*/
	//$tbl="drivers";
	$baseurl=base_url().'organization/front-desk/list-driver/';
	$uriseg ='4';
		//echo $qry;//exit;
	   $p_res=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);

	$data['values']=$p_res['values'];
	
	//print_r($data['values']);exit;
	$driver_trips='';
	$driver_statuses='';
	for($i=0;$i<count($data['values']);$i++){
		$id=$data['values'][$i]['id'];
		$availability=$this->driver_model->getCurrentStatuses($id);
		if($availability==false){
		$driver_statuses[$id]='Available';
		$driver_trips[$id]=gINVALID;
		}else{
		$driver_statuses[$id]='OnTrip';
		$driver_trips[$id]=$availability[0]['id'];
		}
	}
	$data['driver_statuses']=$driver_statuses;
	$data['driver_trips']=$driver_trips;
	if(empty($data['values'])){
				$data['result']="No Results Found !";
				}
	
	for ($i=0;$i<count($data['values']);$i++){
	$driverid=$data['values'][$i]['id'];
	$driver_details[$driverid]=$this->user_model->getVehicleDetails($driverid);
	
	$drivers=$this->vehicle_model->getDriversInfo();
	if($drivers!=false){
	$data['drivers']=$drivers;
	}else{
	$data['drivers']='';
	}
	}
	if(!empty($driver_details)){
		$data['v_details']=$driver_details;
	}
	
	
	
	$data['v_models']=$this->user_model->getArray('vehicle_models');
	$data['v_makes']=$this->user_model->getArray('vehicle_makes');
	$vehicles=$this->vehicle_model->getVehicles();
	if($vehicles!=false){
	$data['vehicles']=$vehicles;
	}else{
	$data['vehicles']='';
	}
	
	$data['page_links']=$p_res['page_links']; 
	$data['title']='List Driver| '.PRODUCT_NAME;
	$page='user-pages/driverList';
	$this->load_templates($page,$data);	
	
	
	}
	else{
	$this->notAuthorized();
	}
	}
		
		public function ShowDriverProfile($param1,$param2){
			if($this->session_check()==true) {
			$data['mode']=$param2;
			if($param2!=null&& $param2!=gINVALID){
			$org_id=$this->session->userdata('organisation_id');
			$arry=array('id'=>$param2,'organisation_id'=>$org_id);
			$data['result']=$this->user_model->getDriverDetails($arry);
			}   
			//trip details
		
			if($param2!=''){
			$tdate=date('Y-m-d');
			$date=explode("-",$tdate);
			$fdate='2014-'.$date[1].'-01';
			$todate='2014-'.$date[1].'-31';
			
			if((isset($_REQUEST['from_pick_date'])|| isset($_REQUEST['to_pick_date']))&& isset($_REQUEST['date_search'])){
			if($_REQUEST['from_pick_date']==null && $_REQUEST['to_pick_date']==null){
			$fdate='2014-'.$date[1].'-01';
			$todate='2014-'.$date[1].'-31';
			} else{
			$fdate=$_REQUEST['from_pick_date'];
			$todate=$_REQUEST['to_pick_date']; }
			$data['trip_tab']='active';
			
			}
			$data['trips']=$this->trip_booking_model->getDriverVouchers($param2,$fdate,$todate);
			//$this->mysession->set('trips',$data['trips']);
			}
			$data['driver_tab']='active';
			//print_r($data['trips']);exit;
			$data['title']='Driver Profile| '.PRODUCT_NAME;
			$page='user-pages/addDrivers';
			$data['d_id']=$param2;
			$data['select']=$this->select_Box_Values();
			$this->load_templates($page,$data);
		
			}
			else{
					$this->notAuthorized();
			}
	}

	public function tripVouchers($param2){
			if($this->session_check()==true) {
		
			//$data['trips']=$this->trip_booking_model->getTripVouchers();
			//print_r($data['trips']);exit;
			$baseurl=base_url().'organization/front-desk/tripvouchers/';
			$per_page=10;
			$uriseg ='4';
			$data['from_date']='';
			$data['to_date']='';
			$data['trip_id']='';
			$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND TV.organisation_id = '.$this->session->userdata('organisation_id').' WHERE T.organisation_id = '.$this->session->userdata('organisation_id').' ';
			if($param2=='1' ){
				$param2='0';
			}
			if((isset($_REQUEST['trip_id'])|| isset($_REQUEST['from_date']) || isset($_REQUEST['to_date']))&& isset($_REQUEST['voucher_search'])){	
				
				if($param2==''){
				$param2='0';
				}
				if($_REQUEST['trip_id']!=null){
					$data['trip_id']=$_REQUEST['trip_id'];
					$qry.='AND T.id ='.$_REQUEST['trip_id'];
					$where_arry['trip_id']=$_REQUEST['trip_id'];
				}
				
				if($_REQUEST['from_date']!=null && $_REQUEST['to_date']!=null){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.pick_up_date >="'.$_REQUEST['from_date'].'" AND T.drop_date <="'.$_REQUEST['to_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];
				}else if($_REQUEST['from_date']!=null && $_REQUEST['to_date']==null ){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.pick_up_date ="'.$_REQUEST['from_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];

				}else if($_REQUEST['from_date']==null && $_REQUEST['to_date']!=null ){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.drop_date ="'.$_REQUEST['to_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];

				}
				if(isset($where_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry));
				}
			}else if($this->mysession->get('condition')!=''){
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['from_date']) || isset($condition['where']['to_date']) ){
				if(isset($condition['where']['trip_id'])){
				$data['trip_id']=$condition['where']['trip_id'];
				$qry.=' AND T.id ='.$condition['where']['trip_id'];
				}
				if($condition['where']['from_date']!=null && $condition['where']['to_date']!=null){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.=' AND T.pick_up_date >="'.$condition['where']['from_date'].'" AND T.drop_date <="'.$condition['where']['to_date'].'"';
				
				}else if($condition['where']['from_date']!=null && $condition['where']['to_date']==null ){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.='AND T.pick_up_date ="'.$condition['where']['from_date'].'"';
				
				}else if($condition['where']['from_date']==null && $condition['where']['to_date']!=null ){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.=' AND T.drop_date ="'.$condition['where']['to_date'].'"';
				

				}
			}
			}
			
						
			$paginations=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['trips']=$paginations['values'];			
			if(empty($data['customers'])){
				$data['result']="No Results Found !";
				}




			$data['title']='Trip Vouchers | '.PRODUCT_NAME;
			$page='user-pages/trip_vouchers';
			$this->load_templates($page,$data);
		
			}else{
				$this->notAuthorized();
			}
	}
	public function select_Box_Values(){
		$tbl_arry=array('marital_statuses','bank_account_types','id_proof_types');
		$this->load->model('user_model');
		for ($i=0;$i<count($tbl_arry);$i++){
		$result=$this->user_model->getArray($tbl_arry[$i]);
		if($result!=false){
		$data[$tbl_arry[$i]]=$result;
		}
		else{
		$data[$tbl_arry[$i]]='';
		}
		}
		return $data;
	}
	
	public function ShowVehicleView($param1,$param2,$param3) {
	
		if($this->session_check()==true) {
		$data['mode']=$param2;
		if($param1=='vehicle'&& $param2==''){
		$this->mysession->delete('vehicle_id');
		} 
		
			
			
				if($param2==''||is_numeric($param2)){
				$data['vehicle_tab']='active';
				$tbl='vehicles';
				$id=$param2;
				if($id!=null){
			
				$this->mysession->set('vehicle_id',$id);
				
				}
				}
				if($param2!=''){
				
					$id=$this->mysession->get('vehicle_id');
				if($id!=''){
						$tdate=date('Y-m-d');
						$date=explode("-",$tdate);
						$fdate='2014-'.$date[1].'-01';
						$todate='2014-'.$date[1].'-31';
						
						if((isset($_REQUEST['from_pick_date'])|| isset($_REQUEST['to_pick_date']))&& isset($_REQUEST['vdate_search'])){
						if($_REQUEST['from_pick_date']==null && $_REQUEST['to_pick_date']==null){
						$fdate='2014-'.$date[1].'-01';
						$todate='2014-'.$date[1].'-31';
						}else{
						$fdate=$_REQUEST['from_pick_date'];
						$todate=$_REQUEST['to_pick_date'];}
						$data['trip_tab']='active';
						}
						
					$data['ve_id']=$id; 
					$data['trips']=$this->trip_booking_model->getVehicleVouchers($id,$fdate,$todate); 
					
					}
					}
				
				if($param2=='insurance' ){ 
				$data['insurance_tab']='active';
				}
				if($param2=='loan'&&($param3== ''|| is_numeric($param3))){
				$data['loan_tab']='active';
				$tbl='vehicle_loans';
				$id=$param3;
				}
				if($param2=='owner'&&($param3== ''|| is_numeric($param3))) {
				$data['owner_tab']='active';
				$tbl='vehicle_owners';
				$id=$param3;
				}
				$org_id=$this->session->userdata('organisation_id');
				//$arry=array('id'=>$id,'organisation_id'=>$org_id);
				
				$data['select']=$this->select_Vehicle_Values();
				
				if($param2!=null&& is_numeric($param2)){
				
				$data['record_values']=$this->user_model->getRecordsById($tbl,$id);
				$data['driver']=$data['record_values']['driver'];
				$data['vehicle']=$data['record_values']['vehicle'];//print_r($data['vehicle']);exit;
				$data['device']=$data['record_values']['device'];
				$insurance_id=$data['vehicle']['vehicles_insurance_id'];
				$loan_id=$data['vehicle']['vehicle_loan_id'];
				$owner_id=$data['vehicle']['vehicle_owner_id'];
				if($insurance_id!=gINVALID && $insurance_id!=0){
				$data['get_insurance']=$this->user_model->getInsurance($insurance_id);

				}
				if($loan_id!=gINVALID && $loan_id!=0){
				$data['get_loan']=$this->user_model->getLoan($loan_id);
				
				}
				if($owner_id!=gINVALID && $owner_id!=0){
				$data['get_owner']=$this->user_model->getOwner($owner_id);
				
				}
				
				if(is_numeric($param2)|| ($this->mysession->get("error")=='true')){
				
				$driver_id=$data['driver']['driver_id'];
				$result=$this->user_model->getDriverNameById($driver_id);
				$data['select']['drivers'][$driver_id]=$result['name'];
				//for device
				$device_id=$data['device']['device_id'];
				$result=$this->user_model->getDeviceImeiById($device_id);
				$data['select']['devices'][$device_id]=$result['imei'];
				}
			}
			//sample ends
				$data['title']="Vehicle Details | ".PRODUCT_NAME;  
				$page='user-pages/addVehicles';
				 $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	public function select_Vehicle_Values(){
	$tbl_arry=array('vehicle_models','drivers','devices','vehicle_ownership_types','vehicle_types','vehicle_makes','vehicle_ac_types','vehicle_fuel_types','vehicle_seating_capacity','vehicle_permit_types');
	$this->load->model('user_model');
	for ($i=0;$i<count($tbl_arry);$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	return $data;
	}
	
	public function ShowVehicleList($param1,$param2) {
	if($this->session_check()==true) {
/*if($this->mysession->get('condition')!=null){
						$condition=$this->mysession->get('condition');
						if(isset($condition['like']['registration_number'])|| isset($condition['where']['vehicle_owner_id']) || isset($condition['where']['vehicle_ownership_types_id'])  || isset($condition['where']['vehicle_model_id']) || isset($condition['where']['status'])){
						}
						else{
						$this->mysession->delete('condition');
						}
						}*/
	$data['reg_num']='';
	$data['owner']='';
	$data['v_model']='';
	$data['ownership']='';
	$data['status_id']='';
	$condition='';
	$per_page=10;
	$like_arry=''; 
	$org_id=$this->session->userdata('organisation_id');
	$where_arry['organisation_id']=$org_id;
	$qry='SELECT V.id
FROM vehicles V where V.organisation_id = '.$this->session->userdata('organisation_id');
	if($param2=='1'){
	$param2='0';
	}
	if($param2==''){
	$this->mysession->delete('condition');

	}
	//for search
	   if( isset($_REQUEST['search'])){ 
	if($param2==''){
	$param2='0';
	}
	if($_REQUEST['status']!=null && $_REQUEST['status']!=-1 ){
	$data['status_id']=$_REQUEST['status'];
	$date_now=date('Y-m-d H:i:s');
	$where_arry['status']=$_REQUEST['status'];
	$qry=' SELECT V.id FROM vehicles AS V LEFT JOIN trips as T ON T.vehicle_id=V.id where V.organisation_id = '.$this->session->userdata('organisation_id').' 
	AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
	AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
	AND T.organisation_id = '.$this->session->userdata('organisation_id').'
	AND T.vehicle_id = V.id 
	AND T.trip_status_id ='.TRIP_STATUS_CONFIRMED;
	}
	if($_REQUEST['status']!=null && $_REQUEST['status']==0 ){
	$data['status_id']=$_REQUEST['status'];
	$date_now=date('Y-m-d H:i:s');
	$where_arry['status']=$_REQUEST['status'];
	$qry='SELECT V.id FROM vehicles V WHERE V.organisation_id = '.$this->session->userdata('organisation_id').' AND V.id NOT IN ('.$qry.')';
	}
	if($_REQUEST['reg_num']!=null){
	$data['reg_num']=$_REQUEST['reg_num'];
	$qry.=' AND V.registration_number LIKE "%'.$_REQUEST['reg_num'].'%" ';
	$like_arry['registration_number']=$_REQUEST['reg_num'];
	}
	if($_REQUEST['owner']>0){
	$data['owner']=$_REQUEST['owner'];
	$qry.=' AND V.vehicle_owner_id ='.$_REQUEST['owner'];
	$where_arry['vehicle_owner_id']=$_REQUEST['owner'];
	}
	if($_REQUEST['ownership']>0){
	$data['ownership']=$_REQUEST['ownership'];
	$qry.=' AND V.vehicle_ownership_types_id ='.$_REQUEST['ownership'];
	$where_arry['vehicle_ownership_types_id']=$_REQUEST['ownership'];
	}
	/*if($_REQUEST['v_type']>0){
	$where_arry['vehicle_type_id']=$_REQUEST['v_type'];
	}*/
	if($_REQUEST['v_model']>0){
	$data['v_model']=$_REQUEST['v_model'];
	$qry.=' AND V.vehicle_model_id ='.$_REQUEST['v_model'];
	$where_arry['vehicle_model_id']=$_REQUEST['v_model'];
	}

	if(isset($where_arry) || isset($like_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
				}
	}
	else if($this->mysession->get('condition')!=''){ 
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['status']) || isset($condition['like']['registration_number']) || isset($condition['where']['vehicle_owner_id'])|| isset($condition['where']['vehicle_ownership_types_id'])|| isset($condition['where']['vehicle_model_id']) ){
			
				if(isset($condition['where']['status'])&& $condition['where']['status']!=-1 ){
				$data['status_id']=$condition['where']['status'];
				$date_now=date('Y-m-d H:i:s');
				//$where_arry['status']=$_REQUEST['status'];
			$qry=' SELECT V.id FROM vehicles AS V LEFT JOIN trips as T ON T.vehicle_id=V.id where V.organisation_id = '.$this->session->userdata('organisation_id').'
	AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
	AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
	AND T.organisation_id = '.$this->session->userdata('organisation_id').'
	AND T.vehicle_id = V.id 
	AND T.trip_status_id ='.TRIP_STATUS_CONFIRMED;
				
				}
				if(isset($condition['where']['status'])&& $condition['where']['status']==0 ){
				$data['status_id']=$condition['where']['status'];
				$date_now=date('Y-m-d H:i:s');
				//$where_arry['status_id']=$_REQUEST['status'];
				$qry='SELECT V.id FROM vehicles V WHERE V.organisation_id = '.$this->session->userdata('organisation_id').' AND V.id NOT IN ('.$qry.')';
				
				}

					if(isset($condition['like']['registration_number'])){
				
				$data['reg_num']=$condition['like']['registration_number']; 
				$qry.=' AND V.registration_number LIKE "%'.$condition['like']['registration_number'].'%" ';
				}
				if(isset($condition['where']['vehicle_owner_id']) && $condition['where']['vehicle_owner_id']!=-1 ){
				$data['owner']=$condition['where']['vehicle_owner_id'];
				$qry.=' AND V.vehicle_owner_id ='.$condition['where']['vehicle_owner_id'];
				}
				if(isset($condition['where']['vehicle_ownership_types_id']) && $condition['where']['vehicle_ownership_types_id']!=-1 ){
				$data['ownership']=$condition['where']['vehicle_ownership_types_id'];
				$qry.=' AND V.vehicle_ownership_types_id ='.$condition['where']['vehicle_ownership_types_id'];
				}
				if(isset($condition['where']['vehicle_model_id'])&& $condition['where']['vehicle_model_id']!=-1 ){
				$data['v_model']=$condition['where']['vehicle_model_id'];
				$qry.=' AND V.vehicle_model_id ='.$condition['where']['vehicle_model_id'];
				}
			}
			}
			
	
	$baseurl=base_url().'organization/front-desk/list-vehicle/';
	$uriseg ='4';

	 $p_res=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);

	$data['values']=$p_res['values'];  
	$vehicle_trips='';
	$vehicle_statuses='';
	for($i=0;$i<count($data['values']);$i++){
		$id=$data['values'][$i]['id'];
		$availability=$this->vehicle_model->getCurrentStatuses($id);
		if($availability==false){
		$vehicle_statuses[$id]='Available';
		$vehicle_trips[$id]=gINVALID;
		}else{
		$vehicle_statuses[$id]='OnTrip';
		$vehicle_trips[$id]=$availability[0]['id'];
		}
	}//print_r($vehicle_statuses);print_r($vehicle_trips);exit;
	$data['vehicle_statuses']=$vehicle_statuses;
	$data['vehicle_trips']=$vehicle_trips;
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	for ($i=0;$i<count($data['values']);$i++){
	//$id=$data['values'][$i]['vehicle_owner_id'];
	//$details[$id]=$this->user_model->getOwnerDetails($id);
	$owners=$this->vehicle_model->getOwners();
	if($owners!=false){
	$data['owners']=$owners;
	}else{
	$data['owners']='';
	}
	$vehicles=$this->vehicle_model->getListVehicles();
	if($vehicles!=false){
	$data['vehicles']=$vehicles;
	}else{
	$data['vehicles']='';
	}
	}
	if(!empty($details)){
	$data['owner_details']=$details;
	}
	$data['page_links']=$p_res['page_links'];
	$tbl_arry=array('vehicle_models','vehicle_types','vehicle_owners','vehicle_makes','vehicle_ownership_types');
	$count=count($tbl_arry);
	for ($i=0;$i<$count;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	$drivers=$this->driver_model->getDrivers();
	if($drivers!=false){
	$data['drivers']=$drivers;
	}else{
	$data['drivers']='';
	}
	$data['title']='List Vehicles | '.PRODUCT_NAME;
	$page='user-pages/vehicleList';
	
	$this->load_templates($page,$data);	
	}
	else{

	$this->notAuthorized();
	
	}
	}
	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){
	return true;
	}
	}
	public function setup_dashboard(){
	if(isset($_REQUEST['setup_dashboard']) ){
	$data=$this->trip_booking_model->getTodaysTripsDriversDetails();
	if($data!=false){
	echo json_encode($data);
	}else{
		echo 'false';
	}
	}
	}
	public function notAuthorized(){
	$data['title']='Not Authorized | '.PRODUCT_NAME;
	$page='not_authorized';
	$this->load->view('admin-templates/header',$data);
	$this->load->view('admin-templates/nav');
	$this->load->view($page,$data);
	$this->load->view('admin-templates/footer');
	
	}
	public function notFound(){
		if($this->session_check()==true) {
		 $this->output->set_status_header('404'); 
		 $data['title']="Not Found";
      	 $page='not_found';
         $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
	}
	}

	public function getNotifications(){
	if(isset($_REQUEST['notify']) ){
	$conditon =array('trip_status_id'=>TRIP_STATUS_PENDING,'CONCAT(pick_up_date," ",pick_up_time) >='=>date('Y-m-d H:i'),'organisation_id'=>$this->session->userdata('organisation_id'));
	//$where_or=array('trip_status_id'=>TRIP_STATUS_CONFIRMED,'trip_status_id'=>TRIP_STATUS_ONTRIP);
	$orderby = ' CONCAT(pick_up_date," ",pick_up_time) ASC';
	$notification=$this->trip_booking_model->getDetails($conditon,$orderby);
	$customers_array=$this->customers_model->getArray();
	$json_data=array('notifications'=>$notification,'customers'=>$customers_array);
	if(count($notification)>0 && count($customers_array) >0 ){
		echo json_encode($json_data);
	}else{
		echo 'false';
	}
	}

	}
	 
}
