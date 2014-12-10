 <?php

 $driver_id=gINVALID;
$name='';
$place_of_birth='';
$dob='';
$blood_group='';
$marital_status_id='';
$children='';
$present_address='';
$permanent_address='';
$district='';
$state='';
$pin_code='';
$phone='';
$mobile='';
$email='';
$date_of_joining='';
$badge='';
$license_number='';
$license_renewal_date='';
$badge_renewal_date='';
$mother_tongue='';
$pan_number='';
$bank_account_number='';
$name_on_bank_pass_book='';
$bank_name='';
$branch='';
$bank_account_type_id='';
$ifsc_code='';
$id_proof_type_id='';
$id_proof_document_number='';
$name_on_id_proof='';
$username =	'';
	

 if($this->mysession->get('post')!=null){
 
 	$data=$this->mysession->get('post');
	$driver_id=$this->mysession->get('driver_id');
	$name=$data['name'];
	$place_of_birth=$data['place_of_birth'];
	$dob=$data['dob'];
	$blood_group=$data['blood_group'];
	$marital_status_id=$data['marital_status_id'];
	$children=$data['children'];
	$present_address=$data['present_address'];
	$permanent_address=$data['permanent_address'];
	$district=$data['district'];
	$state=$data['state'];
	$pin_code=$data['pin_code'];
	$phone=$data['phone'];
	$mobile=$data['mobile'];
	$email=$data['email'];
	$date_of_joining=$data['date_of_joining'];
	$badge=$data['badge'];
	$license_number=$data['license_number'];
	$license_renewal_date=$data['license_renewal_date'];
	$badge_renewal_date=$data['badge_renewal_date'];
	$mother_tongue=$data['mother_tongue'];
	$pan_number=$data['pan_number'];
	$bank_account_number=$data['bank_account_number'];
	$name_on_bank_pass_book=$data['name_on_bank_pass_book'];
	$bank_name=$data['bank_name'];
	$branch=$data['branch'];
	$bank_account_type_id=$data['bank_account_type_id'];
	$ifsc_code=$data['ifsc_code'];
	$id_proof_type_id=$data['id_proof_type_id'];
	$id_proof_document_number=$data['id_proof_document_number'];
	$name_on_id_proof=$data['name_on_id_proof'];
	$username	=	$data['username'];

	$this->mysession->delete('post');
}
else if(isset($result)&&$result!=null){ 
   	$driver_id=$result['id'];
	$name=$result['name'];
	$place_of_birth=$result['place_of_birth'];
	$dob=$result['dob'];
	$blood_group=$result['blood_group']; 
	$marital_status_id=$result['marital_status_id'];
	$children=$result['children'];
	$present_address=$result['present_address'];
	$permanent_address=$result['permanent_address'];
	$district=$result['district'];
	$state=$result['state'];
	$pin_code=$result['pin_code'];
	$phone=$result['phone'];
	$mobile=$result['mobile'];
	$email=$result['email'];
	$date_of_joining=$result['date_of_joining'];
	$badge=$result['badge'];
	$license_number=$result['license_number'];
	$license_renewal_date=$result['license_renewal_date'];
	$badge_renewal_date=$result['badge_renewal_date'];
	$mother_tongue=$result['mother_tongue'];
	$pan_number=$result['pan_number'];
	$bank_account_number=$result['bank_account_number'];
	$name_on_bank_pass_book=$result['name_on_bank_pass_book'];
	$bank_name=$result['bank_name'];
	$branch=$result['branch'];
	$bank_account_type_id=$result['bank_account_type_id'];
	$ifsc_code=$result['ifsc_code'];
	$id_proof_type_id=$result['id_proof_type_id'];
	$id_proof_document_number=$result['id_proof_document_number'];
	$name_on_id_proof=$result['name_on_id_proof'];
	$username	=	$result['username'];

} 



if($this->session->userdata('dbSuccess') != '') { ?>
<div class="success-message">
	<div class="alert alert-success alert-dismissable">
		<i class="fa fa-check"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php 
		echo $this->session->userdata('dbSuccess');
		$this->session->set_userdata(array('dbSuccess'=>''));
		?>
	</div>
</div>
<?php } ?>
<div class="page-outer">
<fieldset class="body-border">

<legend class="body-head">Manage Drivers</legend>

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
	<?php 

	foreach($tabs as $tab=>$attr){
	echo '<li class="'.$attr['class'].'">
		<a href="#'.$attr['tab_id'].'" data-toggle="tab">'.$attr['text'].'</a>
	      </li>';
	}
	?>
	</ul>

	<div class="tab-content">

	<?php if (array_key_exists('d_tab', $tabs)) {?>
 	<div class="<?php echo $tabs['d_tab']['content_class'];?>" id="<?php echo $tabs['d_tab']['tab_id'];?>">       
		<div class="div-with-50-percent-width-with-margin-10">
		<fieldset class="body-border-Driver-View border-style-Driver-view" >
		<legend class="body-head">Personal Details</legend>

		<?php  echo form_open(base_url()."driver/driver_manage");?>

			<div class="form-group">
			<?php 
			echo form_label('Enter Name','usernamelabel'); 
			$input = array('name'=>'driver_name','class'=>'form-control','id'=>'name','placeholder'=>'Enter Name','value'=>$name);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input); 
			echo $this->form_functions->form_error_session('name', '<p class="text-red">', '</p>'); ?>
			</div>

			<div class="form-group">
			<?php 
			echo form_label('Enter Place Of Birth','usernamelabel');
			$input = array('name'=>'place_of_birth','class'=>'form-control','id'=>'place_of_birth','placeholder'=>'Enter Place Of Birth','value'=>$place_of_birth);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input); 
			echo $this->form_functions->form_error_session('place_of_birth', '<p class="text-red">', '</p>'); 			?>
			</div>

			<div class="form-group">
			<?php 
			echo form_label('Date of Birth','usernamelabel'); 
			$input = array('name'=>'dob','class'=>'fromdatepicker form-control' ,'placeholder'=>'Date of Birth','value'=>$dob);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input); 
			echo $this->form_functions->form_error_session('dob', '<p class="text-red">', '</p>'); ?>
			</div>

			<div class="form-group">
			<?php echo form_label('Blood Group','usernamelabel'); 
			   	$disabled=($edit_profile)?'':'disabled';
				$class="form-control";
				$msg="Blood Group ";
				$name="blood_group";
				$id='blood_group';
				$group=array('A+','A-','B+','B-','O+','O-','AB+','AB-');
			echo $this->form_functions->populate_dropdown($name,$group,$blood_group,$class,$id,$msg,$disabled);
				   ?>
			   <?php echo $this->form_functions->form_error_session('blood_group', '<p class="text-red">', '</p>'); ?>
			   <p class="text-red"><?php
			 if($this->session->userdata('blood group') != ''){
				echo $this->session->userdata('blood group');
				$this->session->set_userdata(array('blood group'=>''));
			 }
				?></p>
			</div>

			<div class="form-group">
			<?php echo form_label(' Marital Status','usernamelabel'); 
			
				$disabled=($edit_profile)?'':'disabled';
				$class="form-control";
				$msg="Select Marital Status";
				$name="marital_status_id";
				$id='marital_id';
				echo $this->form_functions->populate_dropdown($name,$select['marital_statuses'],$marital_status_id,$class,$id,$msg,$disabled);
				?>
				<p class="text-red"><?php
				 if($this->session->userdata('marital_status_id') != ''){
					echo $this->session->userdata('marital_status_id');
					$this->session->set_userdata(array('marital_status_id'=>''));
				 }
				?></p>
			</div>

			<div class="form-group">
			<?php 
	
				echo form_label('Children','usernamelabel'); 
				$input = array('name'=>'children','class'=>'form-control','id'=>'children','placeholder'=>'Children','value'=>$children);
				if(!$edit_profile)					
					$input['disabled'] ='';
			   	echo form_input($input);
				echo $this->form_functions->form_error_session('children', '<p class="text-red">', '</p>'); ?>
			</div>

			<div class="form-group">
			<?php echo form_label('Present Address','usernamelabel'); 
				$input = array('name'=>'present_address','class'=>'form-control','id'=>'present_address','placeholder'=>'Present Address','value'=>$present_address,'rows'=>5);
				if(!$edit_profile)					
					$input['disabled'] ='';
			 	echo form_textarea($input); ?>
			   <?php echo $this->form_functions->form_error_session('present_address', '<p class="text-red">', '</p>'); ?>
			</div>
	
			<div class="form-group">
			<?php echo form_label('Permanent Address','usernamelabel'); 
			$input = array('name'=>'permanent_address','class'=>'form-control','id'=>'permanent_address','placeholder'=>'Permanent Address','value'=>$permanent_address,'rows'=>5);
			if(!$edit_profile)					
				$input['disabled'] ='';
			 echo form_textarea($input); ?>
			   <?php echo $this->form_functions->form_error_session('permanent_address', '<p class="text-red">', '</p>'); ?>
			</div>

			<div class="form-group">
			<?php echo form_label('District','usernamelabel');
			$input = array('name'=>'district','class'=>'form-control','id'=>'district','placeholder'=>'District','value'=>$district); 
			if(!$edit_profile)					
				$input['disabled'] ='';
			 echo form_input($input);
			 echo $this->form_functions->form_error_session('district', '<p class="text-red">', '</p>');
			 ?>
			</div>

			<div class="form-group">
			<?php echo form_label('State','usernamelabel');
			$input = array('name'=>'state','class'=>'form-control','id'=>'state','placeholder'=>'State','value'=>$state);
			if(!$edit_profile)					
				$input['disabled'] ='';
			 echo form_input($input); 
			 echo $this->form_functions->form_error_session('state', '<p class="text-red">', '</p>'); ?>
			</div>

			<div class="form-group">
			<?php echo form_label('Pin Code','usernamelabel');
			$input = array('name'=>'pin_code','class'=>'form-control','id'=>'pin_code','placeholder'=>'Pin Code','value'=>$pin_code);
			if(!$edit_profile)					
				$input['disabled'] ='';
			 echo form_input($input);
			 echo $this->form_functions->form_error_session('pin_code', '<p class="text-red">', '</p>');
			?>
			</div>	

			<div class="form-group">
			<?php echo form_label('Phone','usernamelabel');
			$input = array('name'=>'phone','class'=>'form-control','id'=>'phone','placeholder'=>'Phone','value'=>$phone);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input);
			echo $this->form_functions->form_error_session('phone', '<p class="text-red">', '</p>'); 
			?>
			</div>

			<div class="form-group">
			<?php echo form_label('Mobile','usernamelabel');
			$input = array('name'=>'mobile','class'=>'form-control','id'=>'drivermobile','placeholder'=>'Mobile','value'=>$mobile);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input);
			echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>'); 
			?>
		        <div class="hide-me" > <?php echo form_input(array('name'=>"hmob",'value'=>$mobile));?></div>
			</div>

			<div class="form-group">
			<?php echo form_label('E-mail ID','usernamelabel');
			$input = array('name'=>'email','class'=>'form-control','id'=>'driveremail','placeholder'=>'E-mail ID','value'=>$email);
			if(!$edit_profile)					
				$input['disabled'] ='';
			 echo form_input($input);
			 echo $this->form_functions->form_error_session('email', '<p class="text-red">', '</p>'); ?>
			<div class="hide-me"><?php echo form_input(array('name'=>"hmail",'value'=>$email));?></div>
			</div>

			<div class="form-group">
			<?php echo form_label('Date of Joining','usernamelabel');
			$input = array('name'=>'date_of_joining','class'=>'fromdatepicker form-control' ,'placeholder'=>' Date of Joining','value'=>$date_of_joining);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input);?>
			   <?php echo $this->form_functions->form_error_session('date_of_joining', '<p class="text-red">', '</p>'); ?>
			</div>	
			<div class="hide-me"><?php echo form_input(array('name'=>'h_join','value'=>$date_of_joining));?></div>


			<div class="form-group">
			<?php echo form_label('License Number','usernamelabel');
			$input =array('name'=>'license_number','class'=>'form-control','id'=>'license_number','placeholder'=>'License Number','value'=>$license_number);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input); ?>
			   <?php echo $this->form_functions->form_error_session('license_number', '<p class="text-red">', '</p>'); ?>
			</div>
	
			<div class="form-group">
			<?php echo form_label('Date of License Renewal','usernamelabel');
			$input = array('name'=>'license_renewal_date','class'=>'fromdatepicker form-control' ,'placeholder'=>' Date of License Renewal','value'=>$license_renewal_date);
			if(!$edit_profile)					
				$input['disabled'] ='';
			echo form_input($input);?>
			   <?php echo $this->form_functions->form_error_session('license_renewal_date', '<p class="text-red">', '</p>'); 

			  ?>
			</div>
			<div class="hide-me"><?php echo form_input(array('name'=>'h_license','value'=>$license_renewal_date));?></div>


	</fieldset> 
	</div>
		

	<div class="div-with-50-percent-width-with-margin-10">
	<fieldset class="body-border-Driver-View border-style-Driver-view" >
	<legend class="body-head">Other Details</legend>
		
		<div class="form-group">
		<?php echo form_label('Badge','usernamelabel');
			$input = array('name'=>'badge','class'=>'form-control','id'=>'badge','placeholder'=>'Badge','value'=>$badge);
			if(!$edit_profile)					
				$input['disabled'] ='';
		   echo form_input($input);
		   echo $this->form_functions->form_error_session('badge', '<p class="text-red">', '</p>'); ?>
	       	<p class="text-red"><?php
	 if($this->mysession->get('Err_badge') != ''){
		echo $this->mysession->get('Err_badge');
		$this->mysession->delete('Err_badge');
		} ?></p>
		</div>

		<div class="form-group">
		<?php echo form_label('Date of Badge Renewal','usernamelabel');
			$input = array('name'=>'badge_renewal_date','class'=>'fromdatepicker form-control' ,'placeholder'=>'Date of Badge Renewal','value'=>$badge_renewal_date);
		  if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('badge_renewal_date', '<p class="text-red">', '</p>'); 
		?>
		</div>

		<div class="hide-me"><?php echo form_input(array('name'=>'h_badge','value'=>$badge_renewal_date));?></div>
		<div class="form-group">
		<?php echo form_label('Mother Tongue','usernamelabel');
		$input = array('name'=>'mother_tongue','class'=>'form-control','id'=>'mother_tongue','placeholder'=>'Mother Tongue','value'=>$mother_tongue);
		if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input); ?>
		   <?php echo $this->form_functions->form_error_session('mother_tongue', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('Pan Number','usernamelabel');
		$input = array('name'=>'pan_number','class'=>'form-control','id'=>'pan_number','placeholder'=>'Pan Number','value'=>$pan_number);
		 if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('pan_number', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('Bank Account Number','usernamelabel');
		$input = array('name'=>'bank_account_number','class'=>'form-control','id'=>'bank_account number','placeholder'=>'Bank Account Number','value'=>$bank_account_number);
		 if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('bank_account_number', '<p class="text-red">', '</p>'); ?>
		</div>
		<div class="form-group">
		<?php echo form_label('Name on Bank PassBook','usernamelabel');
		$input = array('name'=>'name_on_bank_pass_book','class'=>'form-control','id'=>'name_on_bank_pass_book','placeholder'=>'Name on Bank PassBook','value'=>$name_on_bank_pass_book);
		if(!$edit_profile)					
			$input['disabled'] ='';
		 echo form_input($input);
		 echo $this->form_functions->form_error_session('name_on_bank_pass_book', '<p class="text-red">', '</p>'); ?>
		</div>
		<div class="form-group">
		<?php echo form_label('Bank Name','usernamelabel');
		$input = array('name'=>'bank_name','class'=>'form-control','id'=>'bank_name','placeholder'=>'Bank Name','value'=>$bank_name);
		if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('bank_name', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('Branch','usernamelabel');
		$input = array('name'=>'branch','class'=>'form-control','id'=>'branch','placeholder'=>'Branch','value'=>$branch);
		if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('branch', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('Bank Account','usernamelabel'); ?>
		<?php
			$class="form-control";
			$msg="Select Bank Account";
			$name="bank_account_type_id";
			$disabled=($edit_profile)?'':'disabled';

		echo $this->form_functions->populate_dropdown($name,$select['bank_account_types'],$bank_account_type_id,$class,$id='',$msg,$disabled); 
	
		?>
		<p class="text-red"><?php
	 if($this->session->userdata('bank_account_type_id') != ''){
		echo $this->session->userdata('bank_account_type_id');
		$this->session->set_userdata(array('bank_account_type_id'=>''));
	 }
		?></p>
		</div>

		<div class="form-group">
		<?php echo form_label('IFSC Code','usernamelabel');

		$input = array('name'=>'ifsc_code','class'=>'form-control','id'=>'ifsc_code','placeholder'=>'IFSC Code','value'=>$ifsc_code);
		if(!$edit_profile)					
			$input['disabled'] ='';


		echo form_input($input);
		echo $this->form_functions->form_error_session('ifsc_code', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('ID Proof Type','usernamelabel'); ?>
		<?php
			$class="form-control";
			$msg="Select ID Proof Type";
			$name="id_proof_type_id";
			$disabled=($edit_profile)?'':'disabled';

		echo $this->form_functions->populate_dropdown($name,$select['id_proof_types'],$id_proof_type_id,$class,$id='',$msg,$disabled); 
		?>
		<p class="text-red"><?php
	 if($this->session->userdata('id_proof_type_id') != ''){
		echo $this->session->userdata('id_proof_type_id');
		$this->session->set_userdata(array('id_proof_type_id'=>''));
	 }
		?></p>
		</div>

		<div class="form-group">
		<?php echo form_label('ID Proof Document Number','usernamelabel');
		$input = array('name'=>'id_proof_document_number','class'=>'form-control','id'=>'id_proof_document_number','placeholder'=>'ID Proof Document Number','value'=>$id_proof_document_number);
		if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('id_proof_document_number', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('Name on ID Proof','usernamelabel');
		$input = array('name'=>'name_on_id_proof','class'=>'form-control','id'=>'name_on_id_proof','placeholder'=>'Name on ID Proof','value'=>$name_on_id_proof);
		if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('name_on_id_proof', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('Salary','usernamelabel');
		$input = array('name'=>'salary','class'=>'form-control','id'=>'ifsc_code','placeholder'=>'Salary','value'=>'2500.00','readonly'=>'readonly');
		if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input); ?>
		   <?php echo $this->form_functions->form_error_session('salary', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		<?php echo form_label('Minimum Working Days','usernamelabel');
		$input = array('name'=>'minimum_working_days','class'=>'form-control','id'=>'minimum_working_days','placeholder'=>'Minimum Working Days','value'=>' 25','readonly'=>'readonly');
		if(!$edit_profile)					
			$input['disabled'] ='';
		echo form_input($input);
		echo $this->form_functions->form_error_session('minimum_working_days', '<p class="text-red">', '</p>'); ?>
		</div>


		<?php if($driver_id!='' && $driver_id>0){?>
		<div class="form-group">
		   <?php echo form_label('Username','usernamelabel');
			
			echo form_input(array('name'=>'username','class'=>'form-control','id'=>'username','placeholder'=>'Enter Username','value'=>$username,'disabled'=>''));
		   ?>			
		  <?php echo $this->form_functions->form_error_session('username', '<p class="text-red">', '</p>'); ?>
		</div>
			
		<?php }else{?>
	
		<div class="form-group">
		   <?php echo form_label('Username','usernamelabel');
			
			echo form_input(array('name'=>'username','class'=>'form-control','id'=>'username','placeholder'=>'Enter Username','value'=>$username));
		   ?>			
		   <?php echo $this->form_functions->form_error_session('username', '<p class="text-red">', '</p>'); ?>
		</div>

		<div class="form-group">
		   <?php echo form_label('Password','passwordlabel'); ?>
		   <?php echo form_password(array('name'=>'password','class'=>'form-control','id'=>'password','placeholder'=>'Enter Password','value'=>$password)); ?>			
			<?php echo $this->form_functions->form_error_session('password', '<p class="text-red">', '</p>'); ?>
		</div>
		
		<div class="form-group">
		   <?php echo form_label('Confirm Password','cpasswordlabel'); ?>
		   <?php echo form_password(array('name'=>'cpassword','class'=>'form-control','id'=>'cpassword','placeholder'=>'Enter Confirm password')); ?>			
			<?php echo $this->form_functions->form_error_session('cpassword', '<p class="text-red">', '</p>'); ?>
		</div>
		<?php }?>

		<div class='hide-me'><?php  
		echo form_input(array('name'=>'hidden_id','class'=>'form-control','value'=>$driver_id));?></div>
   		<div class="box-footer">
		
		<?php echo br();
		 if($driver_id==gINVALID||$driver_id==''){
			$btn_name='Save';
		 }else {
			$btn_name='Update';
			}

		if(!$this->session->userdata('driver')){
		echo form_submit("driver-submit",$btn_name,"class='btn btn-primary'"); 
		}?>  
        </div>
	 <?php echo form_close(); ?>
	</fieldset>


	</div>	
        </div>
	
	<?php }?>

	<?php if (array_key_exists('t_tab', $tabs)) {?>
		
	<div class="<?php echo $tabs['t_tab']['content_class'];?>" id="<?php echo $tabs['t_tab']['tab_id'];?>"> 
	<div class="page-outer">
		<fieldset class="body-border">
		<legend class="body-head">Trip</legend>
		<div class="form-group">
		<div class="box-body table-responsive no-padding">
	
		<?php  echo form_open(base_url()."organization/front-desk/driver-profile/".$d_id); ?>
		<table>
			<td><?php echo form_input(array('name'=>'from_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'From Date','value'=>'')); ?></td>
			<td><?php echo form_input(array('name'=>'to_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'To Date','value'=>'')); ?></td>
			<td><?php echo form_submit("date_search","Search","class='btn btn-primary'");
						echo form_close();?></td>
		</table>
			
			
		<table class="table table-hover table-bordered">
			<tbody>
				<tr style="background:#CCC">
					<th>Trip ID</th>
				    <th>Start Date</th>
				    <th>End Date</th>
					<th>Start Time</th>
					<th>End Time</th>
					<!--<th>Releasing Place</th>-->
					<th>Start Km</th>
					<th>Close Km</th>
					<th>Total Km</th>
					<th>Total Hrs</th>
					<th>Vehicle Tariff</th>
					<th>Extra</th>
					<th>Vehicle Number</th>
					<th>Voucher Number</th>
					<th>Over Time</th>
				    
				</tr>
				<?php
					$repeated_dates=array();					
					$tot_nod=$full_tot_km=$tot_parking=$tot_toll=$tot_state_tax=$tot_over_time=$tot_night_halt=$tot_extra=$tot_fuel_extra=$tot_trip_amount= $tth=$ttp=$tto=$dbh=$dbp=$dbo=$i=0;
				if(isset($trips) && $trips!=false){
			
			
			
				
					//for trip data
					for($trip_index=0;$trip_index<count($trips);$trip_index++){
					$tot_km=$trips[$trip_index]['end_km_reading']-$trips[$trip_index]['start_km_reading'];
					$full_tot_km=$full_tot_km+$tot_km;
					$tot_parking=$tot_parking+$trips[$trip_index]['parking_fees'];
					$tot_toll=$tot_toll+$trips[$trip_index]['toll_fees'];
					$tot_state_tax=$tot_state_tax+$trips[$trip_index]['state_tax'];
					$tot_night_halt=$tot_night_halt+$trips[$trip_index]['night_halt_charges'];
					$tot_fuel_extra=$tot_fuel_extra+$trips[$trip_index]['fuel_extra_charges'];
					$tot_trip_amount=$tot_trip_amount+$trips[$trip_index]['total_trip_amount'];
					$extra=$trips[$trip_index]['parking_fees']+$trips[$trip_index]['toll_fees']+$trips[$trip_index]['state_tax']+$trips[$trip_index]['night_halt_charges']+$trips[$trip_index]['fuel_extra_charges'];
					$tot_extra=$tot_extra+$extra;
					$date1 = date_create($trips[$trip_index]['pick_up_date'].' '.$trips[$trip_index]['pick_up_time']);
					$date2 = date_create($trips[$trip_index]['drop_date'].' '.$trips[$trip_index]['drop_time']);
				
					$diff= date_diff($date1, $date2);
					 
					$no_of_days=$diff->d+1; 
					/*if($no_of_days==0){
						$no_of_days='1 Day';
						$day=1;
					}else{
						$no_of_days.=' Days';
						$day=$diff->d;
					}*/
				 
				
					if(!in_array($trips[$trip_index]['pick_up_date'],$repeated_dates)){
					$repeated_dates[$i]=$trips[$trip_index]['pick_up_date'];
					$i++;
					$tot_nod=$tot_nod+$no_of_days;
					}else if($trips[$trip_index]['pick_up_date']!=$trips[$trip_index]['drop_date']){
						if($no_of_days!=1){
						$no_of_days=$no_of_days-1;
						}
						$tot_nod=$tot_nod+$no_of_days;
						$repeated_dates[$i]=$trips[$trip_index]['drop_date'];
					}
				
				
				
					if( $trips[$trip_index]['v_type']=='Hatchback'){
					 $tth=  $tth+$trips[$trip_index]['vehicle_tarif'];
					 $dbh=	$dbh+$trips[$trip_index]['driver_bata'];
					}
					if( $trips[$trip_index]['v_type']=='Premium'){
					 $ttp=  $ttp+$trips[$trip_index]['vehicle_tarif'];
					 $dbp=	$dbp+$trips[$trip_index]['driver_bata'];
					}
					if( $trips[$trip_index]['v_type']!='Premium' && $trips[$trip_index]['v_type']!='Hatchback'){
					 $tto=  $tto+$trips[$trip_index]['vehicle_tarif'];
					 $dbo=	$dbo+$trips[$trip_index]['driver_bata'];
					}
				
				
					?>
					<tr>
						<td><?php echo $trips[$trip_index]['trip_id']; ?></td>
						<td><?php echo $trips[$trip_index]['pick_up_date']; ?></td>
						<td><?php echo $trips[$trip_index]['drop_date']; ?></td>
						<td><?php echo $trips[$trip_index]['pick_up_time']; ?></td>
						<td><?php echo $trips[$trip_index]['drop_time']; ?></td>
						<td><?php echo $trips[$trip_index]['start_km_reading']; ?></td>
						<td><?php echo $trips[$trip_index]['end_km_reading']; ?></td>
						<td><?php echo $tot_km; ?></td>
						<td><?php 
					
						$tot_hrs=(($no_of_days-1)*24)+$hrs=$diff->h; 
					
						echo $tot_hrs;
						?></td>
						<td><?php echo $trips[$trip_index]['vehicle_tarif'];?></td>
						<td><?php echo $extra; ?></td>
						<td><?php echo $trips[$trip_index]['registration_number']; ?></td>
						<td><?php  echo $trips[$trip_index]['voucher_no']; ?></td>
						<td><?php
						if($no_of_days>1){
						$over_time=$tot_hrs-(10*$no_of_days);
						echo $over_time;
						}
						elseif($no_of_days==1){
						$over_time1=$tot_hrs-10;
						if($over_time1>=1){echo $over_time=$over_time1;}else{
						echo $over_time=0;
						}
						} 
					$tot_over_time=$tot_over_time+$over_time;
				
						?></td>
					
				
					</tr>
					<?php } 
						}				
				?>
		
				<?php //endforeach;
				//echo $tot_nod;
				//}
				?>
			</tbody>
		</table>
		<?php if(!empty($trips)){?>
		<table class="table table-hover table-bordered">
			<tbody>
				<tr style="background:#CCC">
					<th></th>
				    <th>Tariff</th>
				    <th>Bata</th>
					<th>Total</th>
				</tr>
				<tr><td>Total Trips Hatchback</td><td><?php echo $tth;?></td><td><?php echo $dbh;?></td><td><?php echo $tth+$dbh;?></td></tr>
				<tr><td>Total Trips Others</td><td><?php echo $tto;?></td><td><?php echo $dbo;?></td><td><?php echo $tto+$dbo;?></td></tr>
				<tr><td>Total Trips Benz</td><td><?php echo $ttp;?></td><td><?php echo $dbp;?></td><td><?php echo $ttp+$dbp;?></td></tr>
				<tr><td>Salary</td><td><?php  if($tot_nod>=20){
				$sal=2500;
				}else{
				$sal=2000;
				}
				echo $sal;
				?> </td><td></td><td><?php echo $sal;?></td></tr>
				<tr><td>Accommodation (-)</td><td><?php $acc=1500; echo $acc; ?></td><td></td><td><?php  echo $acc; ?></td></tr>
				<tr><td>Extra Amount</td><td><?php $ea=0; echo $ea; ?></td><td></td><td><?php  echo $ea; ?></td></tr>
				<tr><td>Over Time</td><td><?php echo $tot_over_time*25;?></td><td></td><td><?php echo $ot=$tot_over_time*25;?></td></tr>
				<tr><td>Others</td><td><?php echo $tot_extra;?></td><td></td><td><?php echo $tot_extra;?></td></tr>
				<tr><td>Advances (-)</td><td><?php $adv=0; echo $adv; ?></td><td></td><td><?php echo $adv; ?></td></tr>
				<tr><td>Expenses (+)</td><td><?php $exp=0; echo $exp; ?></td><td></td><td><?php echo $exp; ?></td></tr>
				<tr style="background:#CCC"><td>Total</td><td></td><td></td><td><?php $total=($tth+$dbh+$tto+$dbo+$ttp+$dbp+$sal+$ea+$ot+$tot_extra+$exp)-($acc+$adv);
							echo $total;
				?></td></tr>
			</tbody>
		</table>
		<?php }?>
	</div>
	</div>
	</fieldset>
	</div>
        </div>
	<?php }?>

	<?php if (array_key_exists('p_tab', $tabs)) {?>

        <div class="<?php echo $tabs['p_tab']['content_class'];?>" id="<?php echo $tabs['p_tab']['tab_id'];?>"> 
		
		<iframe src="<?php echo base_url().'account/front_desk/SupplierPayment/DR'.$driver_id.'/true';?>" height="600px" width="100%">
		<p>Browser not Support</p>
		</iframe>

        </div>

	<?php }?>

	<?php if (array_key_exists('a_tab', $tabs)) {?>
	<div class="<?php echo $tabs['a_tab']['content_class'];?>" id="<?php echo $tabs['a_tab']['tab_id'];?>"> 
		<iframe src="<?php echo base_url().'account/front_desk/DriverPaymentInquiry/DR'.$driver_id.'/true';?>" height="600px" width="100%">
		<p>Browser not Support</p>
		</iframe>
	</div>

	<?php }?>


</div>
</div>	

</fieldset>
</div>
