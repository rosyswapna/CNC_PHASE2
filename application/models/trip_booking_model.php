<?php 
class Trip_booking_model extends CI_Model {
	
	function getDriver($vehicle_id){

	$this->db->from('vehicle_drivers');
	$condition=array('vehicle_id'=>$vehicle_id,'organisation_id'=>$this->session->userdata('organisation_id'),'to_date'=>'9999-12-30');
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results[0]->driver_id;
	}
	}
	function getTripBokkingDate($id){

	$this->db->from('trips');
	$condition=array('id'=>$id,'organisation_id'=>$this->session->userdata('organisation_id'));
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results[0]->booking_date;
	}
	}

	function getVehicle($id){

	$this->db->from('vehicles');
	$condition=array('id'=>$id,'organisation_id'=>$this->session->userdata('organisation_id'));
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results;
	}else{
		return false;
	}
	}

	function getDriverDetails($id){

	$this->db->from('drivers');
	$condition=array('id'=>$id,'organisation_id'=>$this->session->userdata('organisation_id'));
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results;
	}
	}

	function checkTripVoucherEntry($trip_id){

	$this->db->from('trip_vouchers');
    $this->db->where('trip_id',$trip_id);
	
    $results = $this->db->get()->result();
	if(count($results)>0){//print_r($results);
	return $results;
	}else{
	return gINVALID;
	}
	}

	function  bookTrip($data) {
	
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert('trips',$data);
	if($this->db->insert_id()>0){
		return $this->db->insert_id();
	}else{
		return false;
	}
	 
    }	
	

	//generate new voucher for a trip
	function  generateTripVoucher($data,$tariff_id=-1,$trip_data=array()) {

		$this->db->set('created', 'NOW()', FALSE);
		$this->db->insert('trip_vouchers',$data);
		$trip_voucher_id = $this->db->insert_id();
		
		//update trip
		$trip_id = $data['trip_id'];
		$trip_data['trip_status_id'] = TRIP_STATUS_TRIP_BILLED;		
		$trip_data['pick_up_time'] = @$data['trip_starting_time'];		
		$trip_data['drop_time'] = @$data['trip_ending_time'];
		
		$res=$this->updateTrip($trip_data,$trip_id);	
		if($res=true){
			return $trip_voucher_id;
		}else{
			return false;
		}
	}

	
	function  updateTripVoucher($data,$id,$tariff_id=-1,$trip_data=array()) {
		
		$this->db->where('id',$id );
		$this->db->set('updated', 'NOW()', FALSE);
		$this->db->update("trip_vouchers",$data);
		$trip_id=$data['trip_id'];

		$trip_data['trip_status_id'] = TRIP_STATUS_TRIP_BILLED;		
		$trip_data['pick_up_time'] = @$data['trip_starting_time'];		
		$trip_data['drop_time'] = @$data['trip_ending_time'];
		
		
		$res=$this->updateTrip($trip_data,$trip_id);	
		return $id;
	}

	function  updateTrip($data,$id) {
	$this->db->where('id',$id );
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update("trips",$data);
	return true;
	}

	function getDetails($conditon ='',$orderby=''){

	$this->db->from('trips');
	if($conditon!=''){
		$this->db->where($conditon);
	}
	
	if($orderby!=''){
		$this->db->order_by($orderby);
	}
 	$results = $this->db->get()->result();//return $this->db->last_query();exit;
		if(count($results)>0){
		return $results;

		}else{
			return false;
		}
	}
	
	function getTripVouchers(){
$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges,TV.delivery_no,TV.invoice_no, T.id,T.pick_up_city,T.booking_date,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND TV.organisation_id = '.$this->session->userdata('organisation_id').' WHERE T.organisation_id = '.$this->session->userdata('organisation_id');
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}

	function getDriverVouchers($driver_id,$fpdate='',$tpdate=''){ 
			
		$qry='SELECT TV.trip_id,TV.driver_bata,TV.vehicle_tarif,TV.voucher_no,V.registration_number,VT.name as v_type,TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id LEFT JOIN vehicles As V on T.vehicle_id=V.id LEFT JOIN vehicle_types As VT on T.vehicle_type_id=VT.id WHERE TV.organisation_id = '.$this->session->userdata('organisation_id').' AND T.driver_id='.$driver_id;
		if($fpdate!=null && $tpdate!=null){ 
		$qry.=' AND T.pick_up_date BETWEEN "'.$fpdate.'" AND "'.$tpdate .'"';
				}
		if($fpdate!=null && $tpdate==null){
		$qry.=' AND T.pick_up_date= "'.$fpdate.'"';
				}
		if($fpdate==null && $tpdate!=null){
		$qry.=' AND T.drop_date= "'.$tpdate.'"';
				}
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result; 
	}else{
	return false;
	}

	}

	
	function getVehicleVouchers($vehicle_id,$fpdate='',$tpdate=''){
	$qry='SELECT C.name as customer,CG.name as company, TV.trip_starting_time,TV.trip_ending_time,TV.vehicle_tarif,TV.voucher_no,TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON TV.trip_id =T.id LEFT JOIN customers AS C ON T.customer_id=C.id LEFT JOIN customer_groups AS CG ON T.customer_group_id=CG.id WHERE TV.organisation_id = '.$this->session->userdata('organisation_id').' AND T.vehicle_id='.$vehicle_id;
		if($fpdate!=null && $tpdate!=null){ 
		$qry.=' AND T.pick_up_date BETWEEN "'.$fpdate.'" AND "'.$tpdate .'"';
				}
		if($fpdate!=null && $tpdate==null){
		$qry.=' AND T.pick_up_date= "'.$fpdate.'"';
				}
		if($fpdate==null && $tpdate!=null){
		$qry.=' AND T.drop_date= "'.$tpdate.'"';
				}
	$result=$this->db->query($qry);
	$result=$result->result_array();  //print_r($result);exit;
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}
	function getCustomerVouchers($customer_id,$fpdate='',$tpdate=''){
$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND TV.organisation_id = '.$this->session->userdata('organisation_id').' WHERE T.organisation_id = '.$this->session->userdata('organisation_id').' AND T.customer_id='.$customer_id;
	if($fpdate!=null && $tpdate!=null){ 
		$qry.=' AND T.pick_up_date BETWEEN "'.$fpdate.'" AND "'.$tpdate .'"';
				}
		if($fpdate!=null && $tpdate==null){
		$qry.=' AND T.pick_up_date= "'.$fpdate.'"';
				}
		if($fpdate==null && $tpdate!=null){
		$qry.=' AND T.drop_date= "'.$tpdate.'"';
				}
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}

	function selectAvailableVehicles($data){
	//$qry='SELECT V.id as vehicle_id, V.registration_number,V.vehicle_model_id,V.vehicle_make_id FROM vehicles AS V LEFT JOIN trips T ON  V.id =T.vehicle_id AND T.organisation_id = '.$data['organisation_id'].' WHERE V.vehicle_type_id = '.$data['vehicle_type'].' AND V.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND V.organisation_id = '.$data['organisation_id'].' AND ((T.pick_up_date IS NULL AND pick_up_time IS NULL AND T.drop_date IS NULL AND drop_time IS NULL ) OR ((CONCAT(T.pick_up_date," ", T.pick_up_time) NOT BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'") AND (CONCAT( T.drop_date," ", T.drop_time ) NOT BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'")) AND CONCAT( T.pick_up_date," ", T.pick_up_time ) >= CURDATE() AND CONCAT( T.drop_date," ", T.drop_time ) >= CURDATE() AND CONCAT( T.pick_up_date," ", T.pick_up_time ) < "'.$data['dropdatetime'].'" )';
	//echo $qry;exit;	
	$qry='SELECT V1.id as vehicle_id, V1.registration_number,V1.vehicle_model_id,V1.vehicle_make_id FROM vehicles V1 WHERE V1.vehicle_type_id ='.$data['vehicle_type'].' AND V1.vehicle_make_id ='.$data['vehicle_make'].' AND V1.vehicle_model_id ='.$data['vehicle_model'].' AND V1.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND V1.organisation_id = '.$data['organisation_id'].' AND V1.id NOT IN (SELECT V.id FROM vehicles AS V LEFT JOIN trips T ON V.id =T.vehicle_id WHERE V.vehicle_type_id ='.$data['vehicle_type'].' AND V.vehicle_make_id ='.$data['vehicle_make'].' AND V.vehicle_model_id ='.$data['vehicle_model'].' AND V.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND T.trip_status_id="'.TRIP_STATUS_CONFIRMED.'" AND V.organisation_id = '.$data['organisation_id'].' AND (((CONCAT( T.pick_up_date," ", T.pick_up_time ) BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'") OR (CONCAT( T.drop_date," ", T.drop_time ) BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'")) OR ("'.$data['pickupdatetime'].'" BETWEEN CONCAT( T.pick_up_date," ", T.pick_up_time ) AND CONCAT( T.drop_date," ", T.drop_time )) OR ("'.$data['dropdatetime'].'" BETWEEN CONCAT( T.pick_up_date, " ", T.pick_up_time ) AND CONCAT( T.drop_date, " ", T.drop_time ))))';
//echo $qry;exit;	
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}
	function getVehiclesArray($condion=''){
	$this->db->from('vehicles');
	$org_id=$this->session->userdata('organisation_id');
	$this->db->where('organisation_id',$org_id);
	if($condion!=''){
    $this->db->where($condion);
	}
    $results = $this->db->get()->result();
	
				//print_r($results);
		
			for($i=0;$i<count($results);$i++){
			$values[$results[$i]->id]=$results[$i]->registration_number;
			}
			if(!empty($values)){
			return $values;
			}
			else{
			return false;
			}

	}

	function getTodaysTripsDriversDetails(){
$qry='SELECT T.id,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.pick_up_city,T.drop_city,D.id,D.name FROM trips AS T LEFT JOIN drivers AS D ON  T.driver_id =D.id AND T.trip_status_id='.TRIP_STATUS_CONFIRMED.' AND T.organisation_id = '.$this->session->userdata('organisation_id').' WHERE D.organisation_id = '.$this->session->userdata('organisation_id').' AND (T.pick_up_date="'.date('Y-m-d').'" OR T.drop_date="'.date('Y-m-d').'") OR ((T.pick_up_date < "'.date('Y-m-d').'" AND T.drop_date > "'.date('Y-m-d').'"))';

	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}

 /*function getCustomerGroups(){
 $qry='select C.id,C.customer_group_id,CG.name from customers C left join customer_groups CG on C.customer_group_id=CG.id where C.organisation_id='.$this->session->userdata('organisation_id') ;
 $result=$this->db->query($qry);
 $results=$result->result_array(); //print_r($results);exit;
 if(count($results)>0){
	for($i=0;$i<count($results);$i++){
		$c_group[$results[$i]['id']]=$results[$i]['name'];
		}
		return $c_group;
	}else{
		return false;
	}
 }*/



	function getVoucher($id){
	
		$qry = $this->db->get_where('trip_vouchers',array('id'=>$id));
		
		return $qry->row_array();
		
	}




}
?>
