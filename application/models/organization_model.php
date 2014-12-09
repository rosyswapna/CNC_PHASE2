<?php
class Organization_model extends CI_Model {
	function OrganizationOrUserLogin( $username, $password ) {
        $this->db->from('users');
        $this->db->where('username',$username );
		//$user_type_condition='user_type_id = '.ORGANISATION_ADMINISTRATOR.' OR user_type_id = '.FRONT_DESK.' AND user_status_id='.USER_STATUS_ACTIVE;
		//$this->db->where($user_type_condition);
        $this->db->where( 'password', md5($password) );
        $login = $this->db->get()->result();

       
        if ( is_array($login) && count($login) == 1 ) {
			
            $this->details = $login[0];
			if($this->details->user_type_id==ORGANISATION_ADMINISTRATOR || $this->details->user_type_id==FRONT_DESK){
				if($this->details->user_status_id==USER_STATUS_ACTIVE){
					$this->set_session();
          			  return true;
				}else{
				 $this->mysession->set('user_status_error','User is Not Active.');
				return false;
				}
			}else{
				$this->mysession->set('user_type_error','Please Login with Organization or Front desk credentials.');
				return false;
			}
            
        }else{
		$this->mysession->set('password_error','Entered Password is Incorrect');
        return false;
		}
    }
	function getProfile(){
		$query=$this->db->get_where('organisations',array('id'=>$this->session->userdata('organisation_id')));
		if($query->num_rows()>0){
		$org_res=$query->row_array(); 
		$qry=$this->db->get_where('users',array('organisation_id'=>$this->session->userdata('organisation_id')));
		$user_res=$qry->row_array(); //print_r($user_res);
		$data=array('org_res'=>$org_res,'user_res'=>$user_res);
		return $data;
		}else {
		return false;
		}
    }
	function changePassword($data) {
		$this->db->from('users');
        $this->db->where('id',$this->session->userdata('id'));
        $this->db->where( 'password', $data['old_password']);
        $changepassword = $this->db->get()->result();
		if ( is_array($changepassword) && count($changepassword) == 1 ) {
			$dbdata=array('password'=>$data['password']);
			$this->db->where('id',$this->session->userdata('id') );
			$succes=$this->db->update('users',$dbdata);
			if($succes > 0) {
			$this->session->set_userdata(array('dbSuccess'=>'Password changed Successfully'));
			$this->session->set_userdata(array('dbError'=>''));
			return true;
			}
		}else{
			$this->session->set_userdata(array('dbError'=>'Current Password seems to be different'));
			return false;
		}

   	}
	function LoginAttemptsChecks($username) {
		$this->db->from('users');
        $this->db->where('username',$username );
		$login = $this->db->get()->result();
		$this->db->from('user_login_attempts');
		if(count($login) > 0){
        $this->db->where('user_id',$login[0]->id);
        $login_attempts = $this->db->get()->result();
		 if (count( $login_attempts) >= 3 ) {
			$this->session->set_userdata(array('isloginAttemptexceeded'=>true));
			$this->session->set_userdata(array('loginAttemptcount'=>count($login_attempts)));
		}else{
			$this->session->set_userdata(array('isloginAttemptexceeded'=>false));
		}
		}
	}
    function set_session() {
        $this->session->set_userdata( array(
                'id'=>$this->details->id,
                'name'=> $this->details->first_name . ' ' . $this->details->last_name,
                'email'=>$this->details->email,
				'username'=>$this->details->username,
				'organisation_id'=>$this->details->organisation_id,
				'type'=>$this->details->user_type_id,
                'isLoggedIn'=>true,
		'token_pass' =>$this->details->password,
		'fa_account' =>$this->details->fa_account
            )
        );
    }
	
	function clearLoginAttempts($username){
		$tables = array('user_login_attempts');
		$this->db->where('user_id',$this->session->userdata('id'));
		$this->db->delete($tables);

	}
	function recordLoginAttempts($username,$ip_address) {
		$this->db->from('users');
        $this->db->where('username',$username );
		$login = $this->db->get()->result();
		$this->db->from('user_login_attempts');
		if(count($login) > 0){
		$data=array('user_id'=>$login[0]->id,'ip_address'=>$ip_address);
		$this->db->set('created', 'NOW()', FALSE);
		$this->db->insert('user_login_attempts',$data);
		}

	}
	function update($data){
		$orgdbdata = array('name'=>$data['name'],'address'=>$data['addr'],'updated'=>NOW());
		$userdbdata = array('first_name'=>$data['fname'],'last_name'=>$data['lname'],'address'=>$data['addr'],'email'=>$data['mail'],'phone'=>$data['phn']);
		$this->db->where('id',$data['user_id'] );
		$succesuser=$this->db->update('users',$userdbdata);
		if($succesuser>0){
		$this->db->where('id',$data['org_id'] );
		$succes=$this->db->update('organisations',$orgdbdata);
		if($succes > 0) {
		return true;
		}
		}
	}
	function  insertUser($fname,$lname,$addr,$uname,$pwd,$mail,$phn) {
	$org_id=$this->session->userdata('organisation_id');
	if($org_id){
	$data=array('username'=>$uname,'password'=>md5($pwd),'first_name'=>$fname,'last_name'=>$lname,'phone'=>$phn,'address'=>$addr,'user_status_id'=>USER_STATUS_ACTIVE,'user_type_id'=>FRONT_DESK,'email'=>$mail,'organisation_id'=>$org_id);

	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert('users',$data);
	return true;
	  }
    }
	function checkUser($username){
		
		$qry=$this->db->get_where('users',array('username'=>$username,'organisation_id'=>$this->session->userdata('organisation_id')));
		$user_res=$qry->row_array();
		if(count($user_res) > 0){
		return $user_res;
		} else {
		return false;
		}
		}


	function getUserStatus(){
		$qry=$this->db->get('user_statuses');
		$count=$qry->num_rows();
			$s= $qry->result_array();
		
			for($i=0;$i<$count;$i++){
			
			$status[$s[$i]['id']]=$s[$i]['name'];
			}
			return $status;
	}
	function updateUser($data){
		
		$userdbdata = array('first_name'=>$data['firstname'],'last_name'=>$data['lastname'],'address'=>$data['address'],'email'=>$data['email'],'phone'=>$data['phone'],'user_status_id'=>$data['status']);
		$this->db->where('id',$data['id']);
		$this->db->where('organisation_id',$this->session->userdata('organisation_id'));
		$succesuser=$this->db->update('users',$userdbdata);
		if($succesuser>0){
		return true;
		}else{
		return false;
		}
		
	}
	function resetUserPassword($data) {
			$dbdata = array('password'=>$data['password']);
			$this->db->where('id',$data['id'] );
			$this->db->where('organisation_id',$this->session->userdata('organisation_id'));
			$succes=$this->db->update('users',$dbdata);
			if($succes > 0) {
			$this->session->set_userdata(array('dbSuccess'=>'User Password changed Successfully'));
			$this->session->set_userdata(array('dbError'=>''));
			return true;
			}

	}
}
	
?>
