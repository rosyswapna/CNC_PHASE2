<?php
class Driver_model extends CI_Model {


public function addDriverdetails($data,$login=false){

	$org_id=$this->session->userdata('organisation_id');
	if($org_id && $login){
		//add driver login details
		$userdata=array(
				'username'	=> $login['username'],
				'password'	=> md5($login['password']),
				'first_name'	=> $data['name'],
				'phone'		=> $data['mobile'],
				'address'	=> $data['address'],
				'user_status_id'=> USER_STATUS_ACTIVE,
				'user_type_id'	=> DRIVER,
				'email'		=> $data['email'],
				'organisation_id'=> $org_id);

	

		$this->db->set('created', 'NOW()', FALSE);
		$this->db->insert('users',$userdata);
		$login_id = $this->db->insert_id();

		if($login_id > 0){
			$data['login_id'] = $login_id;
			$this->db->set('salary', '2500');
			$this->db->set('minimum_working_days', '25');
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('drivers',$data);
			$driver = $this->db->insert_id();
			if($driver > 0){
				return $driver;
			}else{
				$this->db->delete('users', array('id' => $login_id));
				return false;
			}
			
		}else{//user not added
				return false;
		}
	}else{
		return false;
	}
}

public function getDriverDetails($data){ 
		
	$this->db->from('customers');
	$this->db->where($data);
	return $this->db->get()->result_array();
	
	}
	public function getCurrentStatuses($id){ 
	$qry='SELECT * FROM trips WHERE CONCAT(pick_up_date," ",pick_up_time) <= "'.date("Y-m-d H:i").'" AND CONCAT(drop_date," ",drop_time) >= "'.date("Y-m-d H:i").'" AND driver_id="'.$id.'" AND organisation_id = '.$this->session->userdata('organisation_id').' AND trip_status_id='.TRIP_STATUS_CONFIRMED;
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	
		return $results;
	}else{
		return false;
	}
	}

	public function getDrivers(){ 
	$qry='SELECT D.name,D.id,D.mobile,VD.from_date,VD.to_date,VD.driver_id,VD.vehicle_id FROM drivers AS D LEFT JOIN vehicle_drivers AS VD ON  D.id =VD.driver_id AND D.organisation_id = '.$this->session->userdata('organisation_id').' WHERE VD.organisation_id = '.$this->session->userdata('organisation_id').' AND VD.to_date="9999-12-30"';
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	for($i=0;$i<count($results);$i++){
		$drivers[$results[$i]['vehicle_id']]['driver_name']=$results[$i]['name'];
		$drivers[$results[$i]['vehicle_id']]['mobile']=$results[$i]['mobile'];
		$drivers[$results[$i]['vehicle_id']]['from_date']=$results[$i]['from_date'];

		}
		return $drivers;
	}else{
		return false;
	}
	}

	function getDriversArray($condion=''){
	$this->db->from('drivers');
	if($condion!=''){
    $this->db->where($condion);
	}
    $results = $this->db->get()->result();
	

		for($i=0;$i<count($results);$i++){
		$values[$results[$i]->id]=$results[$i]->name;
		}
		if(!empty($values)){
		return $values;
		}
		else{
		return false;
		}

	}

	public function UpdateDriverdetails($data,$id){
	$arry=array('id'=>$id,'organisation_id'=>$data['organisation_id']);
	$this->db->set('updated', 'NOW()', FALSE);
	$qry=$this->db->where($arry);
	$qry=$this->db->update("drivers",$data);
	
	return true;
	}


}?>
