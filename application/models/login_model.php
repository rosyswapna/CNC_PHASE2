<?php
class Login_model extends CI_Model {

	//get user types except system admin
	function getUserTypes(){
	
		$query = "SELECT * FROM user_types WHERE  name <>'System Administrator' order by name ASC";
		$qry=$this->db->query($query);
	
		$rows = $qry->result_array();
		$i=0;
		foreach($rows as $row){
			$list[$row['id']] = $row['name'];$i++;
		}
		return $list;
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


	//customer login with username and password
	function Login( $username, $password,$type ) {
	
		$filter = array(
				'username' => $username,
				'password' => md5($password),
				'user_type_id' => $type
				);

        	$this->db->from('users');
        	$this->db->where($filter);
        	$login = $this->db->get()->result();
       
        	if ( is_array($login) && count($login) == 1 ) {
			
            		$this->details = $login[0];
			
			if($this->details->user_status_id==USER_STATUS_ACTIVE){
				$this->set_session();
  			  	return true;
			}else{
			 	$this->mysession->set('user_status_error','User Not Active.');
				return false;
			}
			
            
        	}else{
			$this->mysession->set('password_error','Invalid Login');
        		return false;
		}
   	}

	//set customer session 
	function set_session() {
		$this->session->set_userdata( array(
			'id'=>$this->details->id,
			'name'=> $this->details->first_name . ' ' . $this->details->last_name,
			'email'=>$this->details->email,
			'username'=>$this->details->username,
			'organisation_id'=>$this->details->organisation_id,
			'type'=>$this->details->user_type_id,
			'isLoggedIn'=>true,
			'token_pass' =>$this->details->password
			));
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

