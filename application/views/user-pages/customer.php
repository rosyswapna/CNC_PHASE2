<?php 


	$customer_id		=	'-1';
	$name				=	'';	
	$dob				=	'';
	$customer_group_id		= 	'';
	$customer_type_id		= 	'';
	
	$email				= 	'';
	$mobile				= 	'';
	$address			= 	'';

	if($this->mysession->get('post')!=NULL || $values!=false){
	
	if($this->mysession->get('post')!=NULL){
	$data						=	$this->mysession->get('post');//print_r($data);
	if(isset($data['customer_id'])){
	$customer_id = $data['customer_id'];
	}
	
	}else if($values!=false){
	$data =$values;
	$customer_id = $data['id'];
	
	}
	$name				=	$data['name'];	
	$dob				=	$data['dob'];
	$customer_group_id		= 	$data['customer_group_id'];
	if($customer_group_id==gINVALID){$customer_group_id		='';}
	$customer_type_id		= 	$data['customer_type_id'];
	if($customer_type_id==gINVALID){$customer_type_id		='';}
	$email				= 	$data['email'];
	$mobile				= 	$data['mobile'];
	$address			= 	$data['address'];
	}
	$this->mysession->delete('post');
?>
<div class="page-outer">
	   <fieldset class="body-border">
		<legend class="body-head">Customers</legend>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
			<?php 
	if(isset($insurance_tab)){
	$ins_class=$insurance_tab;
	$i_tab="tab-pane active";
	}
	else{
	$ins_class='';
	$i_tab="tab-pane ";
	}
	if(isset($trip_tab)){
	$trip_class=$trip_tab;
	$t_tab="tab-pane active";
	}
	else{
	$trip_class='';
	$t_tab="tab-pane ";
	}
	if(isset($cust_tab)){ 
	$cust_class=$cust_tab;
	$c_tab="tab-pane active";
	}
	else{
	$cust_class='';
	$c_tab="tab-pane";
	}
	if(isset($loan_tab)){
	$loan_class=$loan_tab;
	$l_tab="tab-pane active";
	}
	else{
	$loan_class='';
	$l_tab="tab-pane ";
	}
	if(isset($owner_tab)){
	$owner_class=$owner_tab;
	$o_tab="tab-pane active";
	}
	else{
	$owner_class='';
	$o_tab="tab-pane ";
	}
	if(isset($trip_tab)){
	$trip_class=$trip_tab;
	$t_tab="tab-pane active";
	$c_tab="tab-pane";
	$cust_class='';
	}
	else{
	$trip_class='';
	$t_tab="tab-pane ";
	}
	if(isset($pay_tab)){
	$pay_class=$pay_tab;
	$p_tab="tab-pane active";
	}
	else{
	$pay_class='';
	$p_tab="tab-pane ";
	}
	if(isset($acc_tab)){
	$acc_class=$acc_tab;
	$a_tab="tab-pane active";
	}
	else{
	$acc_class='';
	$a_tab="tab-pane ";
	}
	?>
        <li class="<?php echo $cust_class;?>"><a href="#tab_1" data-toggle="tab">Profile</a></li>
		<?php if(isset($mode)&& $mode!='' ){?>
		<li class="<?php echo $trip_class;?>"><a href="#tab_2" data-toggle="tab">Trip</a></li>
        <li class="<?php echo $pay_class;?>"><a href="#tab_3" data-toggle="tab">Payments</a></li>
         <li class="<?php echo $acc_class;?>"><a href="#tab_4" data-toggle="tab">Accounts</a></li>
       <?php } ?>
    </ul>
    <div class="tab-content">
        <div class="<?php echo $c_tab;?>" id="tab_1">
            		 <div class="profile-body width-80-percent-and-margin-auto">
			<fieldset class="body-border">
   			 <legend class="body-head">Personal Details</legend>
			 <div class="nav-tabs-custom">
				
				<div class="tab-content">
				
				<div class="tab-pane active" id="tab_1">
			<div class="div-with-50-percent-width-with-margin-10">
				<?php echo form_open(base_url().'customers/AddUpdate');?>
				
				<div class="form-group">
					<?php echo form_label('Name','namelabel'); ?>
				    <?php echo form_input(array('name'=>'name','class'=>'form-control','placeholder'=>'Enter Name','value'=>$name)); ?>
					<?php echo $this->form_functions->form_error_session('name', '<p class="text-red">', '</p>'); ?>
				</div>
			
				<div class="form-group">
					<?php echo form_label('Email','emaillabel'); ?>
				    <?php echo form_input(array('name'=>'email','class'=>'form-control','placeholder'=>'Enter email','value'=>$email)); 
					if($customer_id!='' && $customer_id>gINVALID) {  ?><div class="hide-me"> <?php echo form_input(array('name'=>'h_email','class'=>'form-control','value'=>$email)); ?></div><?php } ?>
					<?php echo $this->form_functions->form_error_session('email', '<p class="text-red">', '</p>'); ?>
				</div>
				<div class="form-group">
					<?php echo form_label('Date Of Birth ','doblabel'); ?>
				    <?php echo form_input(array('name'=>'dob','class'=>'form-control initialize-date-picker','placeholder'=>'Enter DOB','value'=>$dob)); 
					 echo $this->form_functions->form_error_session('dob', '<p class="text-red">', '</p>'); ?>
				</div>
				<div class="form-group">
					<?php echo form_label('Phone','phonelabel'); ?>
				    <?php echo form_input(array('name'=>'mobile','class'=>'form-control','placeholder'=>'Enter Phone','value'=>$mobile)); 
					if($customer_id!='' && $customer_id>gINVALID) {  ?><div class="hide-me"> <?php echo form_input(array('name'=>'h_phone','value'=>$mobile)); ?></div><?php } ?>
					<?php echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>'); ?>
				</div>
			
				<div class="form-group">
					<?php echo form_label('Customer Type','ctypelabel'); 
				   $class="form-control customer-type";
					echo $this->form_functions->populate_dropdown('customer_type_id',$customer_types,$customer_type_id,$class,$id='',$msg="Select Customer type");?> 
				</div>
			</div>
			<div class="div-with-50-percent-width-with-margin-10">
				<div class="form-group">
					<?php echo form_label('Customer Group','cgrouplabel'); ?>
				   <?php echo $this->form_functions->populate_dropdown('customer_group_id',$customer_groups,$customer_group_id,$class ='form-control',$id='',$msg="Select Groups"); ?>
					
				</div>
				<div class="form-group">
					<?php echo form_label('Address','addresslabel'); ?>
				    <?php echo form_textarea(array('name'=>'address','class'=>'form-control','placeholder'=>'Enter Address','value'=>$address)); ?>
					<?php echo form_error('address', '<p class="text-red">', '</p>'); ?>
				</div>
		   		<div class="box-footer">
				<?php if($customer_id!='' && $customer_id>gINVALID){ $save_update_button='UPDATE';$class_save_update_button="class='btn btn-primary'"; }else{ $save_update_button='SAVE';$class_save_update_button="class='btn btn-success'"; }?>
				<?php echo form_submit("customer-add-update",$save_update_button,$class_save_update_button).nbs(2).form_reset("customer_reset","RESET","class='btn btn-danger'"); ?> 
				<div class="hide-me"> <?php echo form_input(array('name'=>'customer_id','class'=>'form-control','value'=>$customer_id)); 
				?></div>
			 <?php echo form_close(); ?>
			</div>
			</div>
		 
			</fieldset>
		</div>
        </div>
		 <div class="<?php echo $t_tab;?>" id="tab_2">
            <div class="page-outer">
	   <fieldset class="body-border">
		<legend class="body-head">Trip</legend><div class="form-group">
	<div class="box-body table-responsive no-padding">
	
	<?php //for search ?>
	<?php  echo form_open(base_url()."organization/front-desk/customer/".$c_id); ?>
	<table>
	<td><?php echo form_input(array('name'=>'from_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'From Date','value'=>'')); ?></td>
	<td><?php echo form_input(array('name'=>'to_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'To Date','value'=>'')); ?></td>
	<td><?php echo form_submit("cdate_search","Search","class='btn btn-primary'");
				echo form_close();?></td>
	</table>
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>
						<th>SlNo</th>
					    <th>Date</th>
					    <th>Route</th>
						<th>Kilometers</th>
						<th>No Of Days</th>
						<!--<th>Releasing Place</th>-->
						<th>Trip Amount</th>
					    
					</tr>
					<?php	
						$full_tot_km=$total_trip_amount=0;
					if(isset($trips) && $trips!=false){
						for($trip_index=0;$trip_index<count($trips);$trip_index++){
						$tot_km=$trips[$trip_index]['end_km_reading']-$trips[$trip_index]['start_km_reading'];
						$full_tot_km=$full_tot_km+$tot_km;
						$total_trip_amount=$total_trip_amount+$trips[$trip_index]['total_trip_amount'];
						
						
						$date1 = date_create($trips[$trip_index]['pick_up_date'].' '.$trips[$trip_index]['pick_up_time']);
						$date2 = date_create($trips[$trip_index]['drop_date'].' '.$trips[$trip_index]['drop_time']);
						
						$diff= date_diff($date1, $date2);
						$no_of_days=$diff->d;
						if($no_of_days==0){
							$no_of_days='1 Day';
							
						}else{
							$no_of_days.=' Days';
							
						}

						?>
						<tr>
							<td><?php echo $trip_index+1; ?></td>
							<td><?php echo $trips[$trip_index]['pick_up_date']; ?></td>
							<td><?php echo $trips[$trip_index]['pick_up_city'].' to '.$trips[$trip_index]['drop_city']; ?></td>
							<td><?php echo number_format($tot_km,2); ?></td>
							<td><?php echo $no_of_days; ?></td>
							<!--<td><?php //echo $trips[$trip_index]['releasing_place'];?></td>-->
							<td><?php echo $trips[$trip_index]['total_trip_amount']; ?></td>
						
						</tr>
						<?php } 
						}					
					?>
					<tr>
					<td>Total</td>
					<td></td>
					<td></td>
					<td><?php echo $full_tot_km; ?></td>
					<td></td>
					<td><?php echo number_format($total_trip_amount,2); ?></td>
					</tr>
					<?php //endforeach;
					//}
					?>
				</tbody>
			</table><?php //echo $page_links;?>
		</div>
</div>
</fieldset>
</div>
        </div>
        <div class="<?php echo $p_tab;?>" id="tab_3">
            <iframe src="<?php echo base_url().'account/front_desk/CustomerPayment/C'.$customer_id.'/true';?>" height="600px" width="100%">
		<p>Browser not Support</p>
		</iframe>
        </div>
        <div class="<?php echo $a_tab;?>" id="tab_4">
          <iframe src="<?php echo base_url().'account/front_desk/CustomerPaymentInquiry/C'.$customer_id.'/true';?>" height="600px" width="100%">
		<p>Browser not Support</p>
		</iframe>
        </div>
    </div>
</div>
	
</fieldset>
</div>
