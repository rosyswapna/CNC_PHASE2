<?php
class admin_model extends CI_Model {

    var $details;

    function AdminLogin( $username, $password ) {
        
        $this->db->from('users');
        $this->db->where('username',$username );
		$this->db->where( 'password', md5($password) );
        $login = $this->db->get()->result();
	
	
        
        if ( is_array($login) && count($login) == 1 ) {
			
            $this->details = $login[0];
			if($this->details->user_type_id==SYSTEM_ADMINISTRATOR){
				if($this->details->user_status_id==USER_STATUS_ACTIVE){
					$this->set_session();
          			  return true;
				}else{
				 $this->mysession->set('user_status_error','User is Not Active.');
				return false;
				}
			}else{
				$this->mysession->set('user_type_error','Please Login with Administrators credentials.');
				return false;
			}
            
        }else{
		$this->mysession->set('password_error','Entered Password is Incorrect');
        return false;
		}
    }

    function set_session() {
	
        $this->session->set_userdata( array(
                'id'=>$this->details->id,
                'name'=> $this->details->first_name . ' ' . $this->details->last_name,
                'email'=>$this->details->email,
				'username'=>$this->details->username,
				'type'=>$this->details->user_type_id,
				        'isLoggedIn'=>true,
				'token_pass' =>$this->details->password
				    )
        );
    }

        
    function  insertOrg($name,$fname,$lname,$addr,$uname,$pwd,$mail,$phn ) {
	$data=array('name'=>$name,'address'=>$addr,'status_id'=>'1');
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert('organisations',$data);
	$insert_id=$this->db->insert_id();
	if($insert_id){
	$data2=array('username'=>$uname,'password'=>md5($pwd),'first_name'=>$fname,'last_name'=>$lname,'phone'=>$phn,'address'=>$addr,'user_status_id'=>'1','user_type_id'=>ORGANISATION_ADMINISTRATOR,'email'=>$mail,'organisation_id'=>$insert_id);
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert('users',$data2);
	return true;
	  }
    }

    //select list of organisations as array
    function getOrg(){
	$query=$this->db->get('organisations');
	return $query->result_array();
    }


	//select logged in user details
    function getProfile(){
	$this->db->from('users');
	$this->db->where('id',$this->session->userdata('id'));
	return $this->db->get()->result();
    }


	//update logged in user details
   function updateProfile($data){
		$this->db->where('id',$this->session->userdata('id') );
		$succes=$this->db->update('users',$data);
		if($succes > 0) {
		$this->session->set_userdata(array('dbSuccess'=>'Profile Updated Successfully'));
		}
    }

	//change password for logged in user 
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

	//select organisation row array with name and 
	//organisation admin row array with organisation id
	function checkOrg($name){

		$query=$this->db->get_where('organisations',array('name'=>$name));
		if($query->num_rows()>0){
			$org_res=$query->row_array(); 
			$id=$org_res['id'];
			
			//organisation admin details
			$qry=$this->db->get_where('users',array('organisation_id'=>$id,'user_type_id'=>ORGANISATION_ADMINISTRATOR));
			$user_res=$qry->row_array();
			$data=array('org_res'=>$org_res,'user_res'=>$user_res);
			return $data;
		}
		else 
			return false;
	}

	//status array (active n inactive)
	function getStatus(){
		$qry=$this->db->get('statuses');
		$count=$qry->num_rows();
			$s= $qry->result_array();
		
			for($i=0;$i<$count;$i++){
			
			$status[$s[$i]['id']]=$s[$i]['name'];
			}
			return $status;
	}
		
	
	//user status array
	function getuserStatus(){
		$qry=$this->db->get('user_statuses');
		$count=$qry->num_rows();
			$s= $qry->result_array();
		
			for($i=0;$i<$count;$i++){
			
			$status[$s[$i]['id']]=$s[$i]['name'];
			}
			return $status;
		
		
	}
	
	//reset user password by userid
	function resetOrganizationPasswordAdmin($data) {
			$dbdata = array('password'=>$data['password']);
			$this->db->where('id',$data['id'] );
			$succes=$this->db->update('users',$dbdata);
			if($succes > 0) {
			$this->session->set_userdata(array('dbSuccess'=>'Organization Password changed Successfully'));
			$this->session->set_userdata(array('dbError'=>''));
			return true;
			}

	}

	//newly added
	//change status of organisation and organisation admin by userid and organisation id respectevely
	function orgEnableDisable($data) {
               $dbdata = array('status_id'=>$data['status_id']);
               $this->db->where('id',$data['org_id'] );
               $succes=$this->db->update('organisations',$dbdata);
               if($succes>0){
		       $dbdata = array('user_status_id'=>$data['user_status_id']);
		       $this->db->where('organisation_id',$data['org_id'] );
		       $succesuser=$this->db->update('users',$dbdata);
		       return true;
               }
        }

	function updateOrganization($data){
		$orgdbdata = array('name'=>$data['name'],'address'=>$data['addr']);
		$userdbdata = array('first_name'=>$data['fname'],'last_name'=>$data['lname'],'address'=>$data['addr'],'email'=>$data['mail'],'phone'=>$data['phn']);
		$this->db->where('id',$data['user_id'] );
		$succesuser=$this->db->update('users',$userdbdata);
		if($succesuser>0){
		$this->db->set('updated', 'NOW()', FALSE);
		$this->db->where('id',$data['org_id'] );
		
		$succes=$this->db->update('organisations',$orgdbdata);
		if($succes > 0) {
		return true;
		}
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
   
	
   
}
