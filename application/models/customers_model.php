<?php 
class Customers_model extends CI_Model {
	
	
	public function getCustomerDetails($data){ 
	//$this->db->select('id,name,email,mobile');
	$this->db->from('customers');
	if($data!=''){
		$this->db->where($data);
	}
	return $this->db->get()->result_array();
	
	}

	/*
	public function addCustomer($data){
		$data['organisation_id']=$this->session->userdata('organisation_id');
		$data['user_id']=$this->session->userdata('id');
		
 		if($data['mobile']!=''){
		$condition['mobile']=$data['mobile'];
		$condition['organisation_id']=$this->session->userdata('organisation_id');
		$res=$this->getCustomerDetails($condition);
		if(count($res)==0){
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('customers',$data);
			$insert_id=$this->db->insert_id();

			if($insert_id > 0){

				return $insert_id;
			}else{
				return false;
			}
		}else{
			return $res[0]['id'];
		}
	
		}else{
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('customers',$data);
			$insert_id=$this->db->insert_id();

			if($insert_id > 0){
				return $insert_id;
			}else{
				return false;
			}

		}
	}
*/


	public function addCustomer($data,$login=false){

		$org_id=$this->session->userdata('organisation_id');
		if($org_id && $login){
			
			//add customer login details
			$userdata=array(
				'username'=>$login['username'],
				'password'=>md5($login['password']),
				'first_name'=>$data['name'],
				'phone'=>$data['mobile'],
				'address'=>$data['address'],
				'user_status_id'=>USER_STATUS_ACTIVE,
				'user_type_id'=>CUSTOMER,
				'email'=>$data['email'],
				'organisation_id'=>$org_id);

			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('users',$userdata);
			$login_id = $this->db->insert_id();
			
			//insert customer
			if($login_id > 0){
				$data['organisation_id'] = $org_id;
				$data['user_id']=$this->session->userdata('id');
				$data['login_id'] = $login_id;
				
				//mobile validation for customer
				$insert_customer = true;
		 		if($data['mobile']!=''){
					$res=$this->getCustomerDetails(array('mobile'=>$data['mobile'],'organisation_id'=>$org_id));
					if(count($res)==0){
						$insert_customer = true;
					}else{
						$insert_customer = false;
					}
	
				}else{
					$insert_customer = true;
				}

				//validation true insert data and return insert customer id
				if($insert_customer){
					$this->db->set('created', 'NOW()', FALSE);
					$this->db->insert('customers',$data);
					$customer_id=$this->db->insert_id();

					if($customer_id > 0)
						return $customer_id;
					
				}

				// customer not inserted , delete user
				$this->db->delete('users', array('id' => $login_id));
				return false;
		
			}else{//user not added
				return false;
			}

		}else{//organisation id not in session and customer login details not found
			return false;
		}
	}


	public function getCurrentStatuses($id){ 
	$qry='SELECT * FROM trips WHERE CONCAT(pick_up_date," ",pick_up_time) <= "'.date("Y-m-d H:i").'" AND CONCAT(drop_date," ",drop_time) >= "'.date("Y-m-d H:i").'" AND customer_id="'.$id.'" AND organisation_id = '.$this->session->userdata('organisation_id').' AND trip_status_id='.TRIP_STATUS_CONFIRMED;
	$results=$this->db->query($qry);
	$results=$results->result_array();//echo $this->db->last_query();
	if(count($results)>0){
	
		return $results;
	}else{
		return false;
	}
	}
	
	function  updateCustomers($data,$id) {
	$this->db->where('id',$id );
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update("customers",$data);
	return true;
	}
	
	public function getArray(){
		$qry=$this->db->get('customers');
		$count=$qry->num_rows();
		$l= $qry->result_array();
		
			for($i=0;$i<$count;$i++){
			$values[$l[$i]['id']]=$l[$i]['name'];
			}
			if(!empty($values)){
			return $values;
			}
			else{
			return false;
			}
	}

	//get all customer ids
	public function getAllIds(){

		$qry=$this->db->select('id');
		$this->db->from('customers');
		$qry=$this->db->get();
		$count=$qry->num_rows();
		$result= $qry->result_array();
		for($i=0;$i<$count;$i++){
				$values[$result[$i]['id']]=$result[$i]['id'];
				}
	
		return $values;
	
	}
	//get all customer group ids
	public function getAllGrpIds(){

		$qry=$this->db->select('id');
		$this->db->from('customer_groups');
		$qry=$this->db->get();
		$count=$qry->num_rows();
		$result= $qry->result_array();
		for($i=0;$i<$count;$i++){
				$values[$result[$i]['id']]=$result[$i]['id'];
				}
	
		return $values;
	
	}


	public function getCustomersById($c_id){
	$qry='select id,name,mobile,email from customers where customer_group_id='.$c_id.' and organisation_id='.$this->session->userdata('organisation_id');
	$result=$this->db->query($qry);
	$result=$result->result_array();
	return $result;
	
	}
}
?>
