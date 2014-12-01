<?php    if($this->session->userdata('dbSuccess') != '') { ?>
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
       <?php    } 
if(!isset($trip_pick_date)){
$trip_pick_date='';
}
if(!isset($trip_drop_date)){
$trip_drop_date='';
}
if(!isset($customer)){
$customer='';
}
if(!isset($driver_id)){
$driver_id='';
}
if(!isset($vehicle_id)){
$vehicle_id='';
}
if(!isset($trip_status_id)){
$trip_status_id='';
}
if(!isset($customer_group_id)){
$customer_group_id='';
}
if(!isset($customer_name)){
$customer_name='';
}
$page=$this->uri->segment(4);
if($page==''){
$trip_sl_no=1;
}else{
$trip_sl_no=$page;
}
?>

<div class="trips">

<div class="box">
    <div class="box-body1">
<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">Trips</legend>
		<div class="box-body table-responsive no-padding">
			
			<?php echo form_open(base_url()."organization/front-desk/trips"); ?>
			<table class="table list-trip-table no-border">
				<tbody>
					<tr>
						<!---->
					    <td><?php echo form_input(array('name'=>'trip_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'Pick up Date','value'=>$trip_pick_date)); ?></td>
					    <td><?php  echo form_input(array('name'=>'trip_drop_date','class'=>'dropdatepicker initialize-date-picker form-control' ,'placeholder'=>'Drop Date','value'=>$trip_drop_date)); ?></td>
						 <td><?php $class="form-control";
							  $id='vehicles';
						echo $this->form_functions->populate_dropdown('vehicles',$vehicles,$vehicle_id,$class,$id,$msg="Vehicle");?></td>
						 <td><?php $class="form-control";
							  $id='drivers';
						echo $this->form_functions->populate_dropdown('drivers',$drivers,$driver_id,$class,$id,$msg="Driver");?></td>
						<td><?php $class="form-control";
							  $id='trip-status';
						echo $this->form_functions->populate_dropdown('trip_status_id',$trip_statuses,$trip_status_id,$class,$id,$msg="Trip Status");?></td>
					     <td><?php $class="form-control";
							  $id='cgroups';
						echo $this->form_functions->populate_dropdown('cgroups',$customer_groups,$customer_group_id,$class,$id,$msg="Company");?></td>
						<td><?php echo form_input(array('name'=>'customer','class'=>'customer form-control' ,'placeholder'=>'Name','value'=>$customer_name,'id'=>'c_name')); ?></td>
						<td><?php echo form_submit("trip_search","Search","class='btn btn-primary'");
echo form_close();?></td>
					<td><?php echo form_button('print-trip','Print',"class='btn btn-primary print-trip'"); ?></td>
						
					</tr>
				</tbody>
			</table>
		</div>
	
	<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>
		
		<div class="box-body table-responsive no-padding trips-table">
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>	
						
					    <th style="width:2%">Trip ID </th>
					    <th style="width:4%">Booking Date</th>
						<!--<th style="width:15%">Customer</th>-->
					    <th style="width:4%">Pickup Date</th>
						 <th style="width:4%">Pickup Time</th>
					    
					    <th  style="width:11%">Location</th>
					    <th  style="width:11%">Guest</th>
						<!--<th style="width:11%">Visit To</th>-->
						<th style="width:11%">Called By</th>						
						<th style="width:11%">Vehicle</th>
						<th style="width:11%">Driver</th>
						 <th style="width:11%">Status</th>
						 <th style="width:13%">Action</th>
					</tr>
					<?php
					
					
					for($trip_index=0;$trip_index<count($trips);$trip_index++){
						$pickdate=$trips[$trip_index]['pick_up_date'].' '.$trips[$trip_index]['pick_up_time'];
						$dropdate=$trips[$trip_index]['drop_date']." ".$trips[$trip_index]['drop_time'];

						$date1 = date_create($pickdate);
						$date2 = date_create($dropdate);
						
						$diff= date_diff($date1, $date2);
						if($diff->d > 0 && $diff->h >= 0 && $diff->i >=1 ){
							$no_of_days=$diff->d+1;
						}else{
							$no_of_days=$diff->d;
						}
					?>
					<tr>
						
						<td><?php echo $trips[$trip_index]['trip_id'];?></td>
						<td><?php echo $trips[$trip_index]['booking_date'];?></td>
					   <!-- <td><?php echo $customers[$trips[$trip_index]['customer_id']].br();
						if($trips[$trip_index]['customer_group_id']==gINVALID || $trips[$trip_index]['customer_group_id']==0){echo '';}else{ echo $customer_groups[$trips[$trip_index]['customer_group_id']];}?></td>-->
					    <td><?php echo $trips[$trip_index]['pick_up_date']; ?></td>
						<td><?php echo $trips[$trip_index]['pick_up_time']; ?></td>
					    
						 <td><?php if(isset($trips[$trip_index]['pick_up_city'])){echo $trips[$trip_index]['pick_up_city'];}else{};
									if($trips[$trip_index]['pick_up_area']!=null){ echo ",".br().$trips[$trip_index]['pick_up_area'];}else{};
									if($trips[$trip_index]['drop_city']!=null){   echo "-".br().$trips[$trip_index]['drop_city'];}else{};
									/*if(isset($trips[$trip_index]['drop_area'])){ echo ",".$trips[$trip_index]['drop_area'].br();}else{};*/
						 ?></td>
					  <td>
					  <?php if($trips[$trip_index]['guest_id']==gINVALID || $trips[$trip_index]['guest_id']==0){echo '';}else{ echo $trips[$trip_index]['guest_name'].br().$trips[$trip_index]['guest_info'];
					    } ?>
					  </td>
						<!-- <td><?php echo $trips[$trip_index]['drop_city'].br();
									echo $trips[$trip_index]['drop_area'];
						 ?></td>-->
					 <td><?php if($trips[$trip_index]['customer_id']==gINVALID || $trips[$trip_index]['customer_id']==0){echo '';}else{ echo $trips[$trip_index]['customer_name'].br();
					    }
					    if($trips[$trip_index]['customer_group_id']==gINVALID || $trips[$trip_index]['customer_group_id']==0){echo '';}else{ echo $trips[$trip_index]['customer_group'].br();
					    }
					    if($trips[$trip_index]['customer_id']==gINVALID || $trips[$trip_index]['customer_id']==0){echo '';}else{ echo $trips[$trip_index]['customer_mobile'];
					    }
					    ?></td>
					    <td><?php 
					    if($trips[$trip_index]['vehicle_id']==gINVALID || $trips[$trip_index]['vehicle_id']==0){echo 'Vehicle Not Allocated'.br();}else{ echo $trips[$trip_index]['registration_number'].br();
					    }
					    if($trips[$trip_index]['vehicle_model_id']==gINVALID || $trips[$trip_index]['vehicle_model_id']==0){echo '';}else{ echo $trips[$trip_index]['model'].br();
					    }
					    /*if($trips[$trip_index]['vehicle_ownership_types_id']==gINVALID || $trips[$trip_index]['vehicle_ownership_types_id']==0){echo '';}else{ echo $trips[$trip_index]['ownership'].br();
					    }*/
					    ?></td>
					  <td><?php
						if($trips[$trip_index]['driver_id']==gINVALID || $trips[$trip_index]['driver_id']==0){echo 'Driver Not Allocated';}else{ echo $trips[$trip_index]['driver'].br().$trips[$trip_index]['driver_info'];
					    }
						?></td>
					    <td>
							<span class="label <?php echo $status_class[$trips[$trip_index]['trip_status_id']]; ?>"><?php echo $trip_statuses[$trips[$trip_index]['trip_status_id']];?></span> 
						
						</td>	
						
						
						<td>

	<?php 

	if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_CONFIRMED || $trips[$trip_index]['trip_status_id']==TRIP_STATUS_PENDING ) { 

		echo anchor_popup_default(base_url().'organization/front-desk/trip-booking/'.$trips[$trip_index]['trip_id'],'<span></span>',array('class'=>'fa fa-edit ','title'=>'Edit')).nbs(5);

		if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_CONFIRMED) { 

			echo "<a href=".base_url().'trip/complete/'.$trips[$trip_index]['trip_id']."/".$this->uri->segment(4)." title='Complete' class='fa fa-caret-square-o-right complete-trip'><span vehicle_model_id='".$trips[$trip_index]['vehicle_model_id']."' tarrif_id='".$trips[$trip_index]['tariff_id']."' trip_id='".$trips[$trip_index]['trip_id']."'></span></a>"; 
		
		} 
		
	}else if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_TRIP_COMPLETED){ 
		echo "<a href=".base_url().'trip/view/'.$trips[$trip_index]['trip_id']." class='fa fa-print for print' target='_blank' title='Print'></a>".nbs(5)."<span title='Voucher' class='sp-btn fa fa-mail-forward voucher' trip_id='".$trips[$trip_index]['trip_id']."' driver_id='".$trips[$trip_index]['driver_id']."' vehicle_model_id='".$trips[$trip_index]['vehicle_model_id']."' vehicle_ac_type_id='".$trips[$trip_index]['vehicle_ac_type_id']."' tarrif_id='".$trips[$trip_index]['tariff_id']."' type='button' no_of_days='".$no_of_days."' pick_up_time='".$trips[$trip_index]['pick_up_time']."' pick_up_date='".$trips[$trip_index]['pick_up_date']."' drop_date='".$trips[$trip_index]['drop_date']."' company_name='".$trips[$trip_index]['company_name']."' customer_name='".$trips[$trip_index]['customer_name']."' model='".$trips[$trip_index]['model']."' vehicle_no='".$trips[$trip_index]['registration_number']."' description='".$trips[$trip_index]['remarks']."' ></span>"; 
		
	}else if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_TRIP_BILLED){ 
		echo "<span title='Voucher' class='sp-btn fa fa-mail-forward voucher' trip_id='".$trips[$trip_index]['trip_id']."' driver_id='".$trips[$trip_index]['driver_id']."' tarrif_id='".$trips[$trip_index]['tariff_id']."' type='button' no_of_days='".$no_of_days."' pick_up_time='".$trips[$trip_index]['pick_up_time']."' vehicle_ac_type_id='".$trips[$trip_index]['vehicle_ac_type_id']."' vehicle_model_id='".$trips[$trip_index]['vehicle_model_id']."' pick_up_date='".$trips[$trip_index]['pick_up_date']."' drop_date='".$trips[$trip_index]['drop_date']."' company_name='".$trips[$trip_index]['company_name']."' customer_name='".$trips[$trip_index]['customer_name']."' model='".$trips[$trip_index]['model']."' vehicle_no='".$trips[$trip_index]['registration_number']."' description='".$trips[$trip_index]['remarks']."' ></span>"; 
		
	} 
	?>
	</td>
					</tr>
					<?php 
						$trip_sl_no++;
						}
					?>
				</tbody>
			</table><?php echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>
</div>

</div><!-- /.box-body -->
   
	<div class='overlay-container'>
   		<div class="overlay modal"></div>
		<div class="loading-img"></div>
		<div class="modal-body border-2-px box-shadow">
			<div class="profile-body width-80-percent-and-margin-auto ">
			<fieldset class="body-border">
   			 <legend class="body-head">Trip Voucher</legend>

				<!-- section 1 start -->
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="div-with-20-percent-width-with-margin-10">
				
						<div class="form-group">
						   <?php echo form_label('Voucher Number','voucherno'); ?>
						   <?php echo form_input(array('name'=>'voucherno','class'=>'form-control voucherno','id'=>'voucherno','placeholder'=>'Voucher No')); ?>			
							<span class="voucher-no-error text-red"></span>
						</div>
					</div>
					<div class="div-with-20-percent-width-with-margin-10">
				
						<div class="form-group">
						   <?php echo form_label('Company Name','company'); ?>
						   <?php echo form_input(array('name'=>'company','class'=>'form-control company','id'=>'company','placeholder'=>'Company Name','readonly'=>'true')); ?>			
						</div>
					</div>
					<div class="div-with-20-percent-width-with-margin-10">
				
						<div class="form-group">
						   <?php echo form_label('Customer Name','customer'); ?>
						   <?php echo form_input(array('name'=>'customer','class'=>'form-control customer','id'=>'customer','placeholder'=>'Customer Name','readonly'=>'true')); ?>			
						</div>
					</div>
					<div class="div-with-67-percent-width-with-margin-10">
				
						<div class="form-group">
						   <?php echo form_label('Description','description'); ?>
						   <?php echo form_input(array('name'=>'description','class'=>'form-control description','id'=>'description','placeholder'=>'Description')); ?>			
						</div>
					</div>
				</div>
				<!-- section 1 ends  -->
				
				<!-- section 2 start -->				
				<div class="row-source-100-percent-width-with-margin-8">
					
					<div class="div-with-20-percent-width-with-margin-10">
				
						<div class="form-group">
						   <?php echo form_label('Start Date','startdt'); ?>
						   <?php echo form_input(array('name'=>'startdt','class'=>'form-control startdt','id'=>'startdt','placeholder'=>'Start Date','readonly'=>'true')); ?>			
							<span class="start-dt-error text-red"></span>
						</div>
						
						<div class="form-group">
						   <?php echo form_label('Start KM Reading','startkm'); ?>
						   <?php echo form_input(array('name'=>'startkm','class'=>'form-control startkm','id'=>'startkm','placeholder'=>'Enter Start K M')); ?>			
							<span class="start-km-error text-red"></span>
						</div>
					</div>
					
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('End Date','enddt'); ?>
							<?php echo form_input(array('name'=>'enddt','class'=>'form-control enddt','placeholder'=>'End Date')); ?>
							<span class="end-dt-error text-red"></span>
						</div>
						<div class="form-group">
							<?php echo form_label('End Km Reading','endkm'); ?>
							<?php echo form_input(array('name'=>'endkm','class'=>'form-control endkm','placeholder'=>'Enter End KM')); ?>
							<span class="end-km-error text-red"></span>
						</div>
					</div>
					<div class="div-with-20-percent-width-with-margin-10">

						<div class="form-group">
							<?php echo form_label('Trip Starting Time','tripstartingtime'); ?>
							<?php echo form_input(array('name'=>'tripstartingtime','class'=>'form-control tripstartingtime format-time','placeholder'=>'Enter Trip Starting Time')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Total Time','triptime'); ?>
							<?php echo form_input(array('name'=>'triptime','class'=>'form-control triptime format-time','placeholder'=>'Total Trip Time','readonly'=>'true')); 
							?>
						</div>
					</div>
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('Trip Ending Time','tripendingtimelabel'); ?>
							<?php echo form_input(array('name'=>'tripendingtime','class'=>'form-control tripendingtime format-time','placeholder'=>'HH:MM')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Total Km Reading','totalkm'); ?>
							<?php echo form_input(array('name'=>'totalkm','class'=>'form-control totalkm','placeholder'=>'Total KM','readonly'=>'true')); ?>
							<span class="total-km-error text-red"></span>
						</div>

					
					</div>
					
					
					<div class="form-group hide-me">
						<?php echo form_label('Gariage Clossing KM','gariageclosingkm'); ?>
						<?php echo form_input(array('name'=>'garageclosingkm','class'=>'form-control garageclosingkm','placeholder'=>'Enter Gariage closing km')); ?>
						<span class="garage-km-error text-red"></span>
					</div>
					<div class="form-group hide-me">
						<?php echo form_label('Gariage Closing Time','gariageclosingtime'); ?>
						<?php echo form_input(array('name'=>'garageclosingtime','class'=>'form-control garageclosingtime initialize-time-picker','placeholder'=>'Enter Gariage Closing Time')); 
						?>
						<span class="garage-time-error text-red"></span>
					</div>
						
					
				</div>
				<!-- section 2 ends -->

				<!-- section 3 start -->
				<div class="row-source-100-percent-width-with-margin-8">
					<!-- col 1 -->
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('Vehicle','model'); ?>
							<?php echo form_input(array('name'=>'model','class'=>'form-control model','placeholder'=>'Vehicle Model','readonly'=>'true')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Base KM','basekm'); ?>
							<?php echo form_input(array('name'=>'basekm','class'=>'form-control basekm','placeholder'=>'Base KM')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Base Hrs','basehrs'); ?>
							<?php echo form_input(array('name'=>'basehrs','class'=>'form-control basehrs format-time','placeholder'=>'Base Hours')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Per Day KM','perdaykm'); ?>
							<?php echo form_input(array('name'=>'perdaykm','class'=>'form-control perdaykm','placeholder'=>'Per Day KM')); 
							?>
						</div>
						
					</div>
					<!-- col 2 -->
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('Vehicle No.','vehicleno'); ?>
							<?php echo form_input(array('name'=>'vehicleno','class'=>'form-control vehicleno','placeholder'=>'Vehicle Number','readonly'=>'true')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Base Amount','baseamount'); ?>
							<?php echo form_input(array('name'=>'baseamount','class'=>'form-control baseamount','placeholder'=>'Base Amount')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Base Hrs Amount','basehrslabel'); ?>
							<?php echo form_input(array('name'=>'basehrsamount','class'=>'form-control basehrsamount','placeholder'=>'Base Hours ')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Per Day KM Amount','perdaykmamountlabel'); ?>
							<?php echo form_input(array('name'=>'perdaykmamount','class'=>'form-control perdaykmamount','placeholder'=>'Per Day KM Amount')); 
							?>
						</div>
						
					</div>
					<!-- col 3 -->
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('Releasing Place','releasingplace'); ?>
							<?php echo form_input(array('name'=>'releasingplace','class'=>'form-control releasingplace','placeholder'=>'Enter Releasing Place')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Additional KM','adtkm'); ?>
							<?php echo form_input(array('name'=>'adtkm','class'=>'form-control adtkm','placeholder'=>'Additional KM','readonly'=>'true')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Additional Hrs','adthrs'); ?>
							<?php echo form_input(array('name'=>'adthrs','class'=>'form-control adthrs','placeholder'=>'Additional Hrs','readonly'=>'true')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Additional KM','adtperdaykmlabel'); ?>
							<?php echo form_input(array('name'=>'adtperdaykm','class'=>'form-control adtperdaykm','placeholder'=>'Additional KM','readonly'=>'true')); 
							?>
						</div>
						
					</div>
					<!-- col 4 -->
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('No of Days','daysno'); ?>
							<?php echo form_input(array('name'=>'daysno','class'=>'form-control daysno','placeholder'=>'No Of Days','readonly'=>'true')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Adl KM Rate','adlkmrate'); ?>
							<?php echo form_input(array('name'=>'adlkmrate','class'=>'form-control adlkmrate','placeholder'=>'Additional KM Rate')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Adl Hr Rate','adlhrrate'); ?>
							<?php echo form_input(array('name'=>'adlhrrate','class'=>'form-control adlhrrate','placeholder'=>'Additional Hr Rate')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Adl KM Rate','adtperdaykmratelabel'); ?>
							<?php echo form_input(array('name'=>'adtperdaykmrate','class'=>'form-control adtperdaykmrate','placeholder'=>'Additional KM Rate')); 
							?>
						</div>
						
					</div>
				</div>
				<!-- section 3 end -->

				<!--  section 4 start -->
				<div class="row-source-100-percent-width-with-margin-8">
					<!-- col 1 -->
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('State Tax','statetax'); ?>
							<?php echo form_input(array('name'=>'statetax','class'=>'form-control statetax','placeholder'=>'Enter State Tax')); 
							?>
						</div>
						<div class="form-group">
							<?php echo form_label('Night Halt','nighthalt'); ?>
							<?php echo form_input(array('name'=>'nighthalt','class'=>'form-control nighthalt','placeholder'=>'Enter Night Halt')); 
							?>
						</div>
						
					</div>
					<!-- col 2 -->
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('Driver Bata','driverbatalabel'); ?>
							<?php echo form_input(array('name'=>'driverbata','class'=>'form-control driverbata','placeholder'=>'Enter Driver Bata')); ?>
					
						</div>
						<div class="form-group">
							<?php echo form_label('Parking Fee','parking'); ?>
							<?php echo form_input(array('name'=>'parkingfee','class'=>'form-control parkingfee','placeholder'=>'Enter Parking Fee')); ?>
					
						</div>
						
					</div>
					<!-- col 3 -->
					<div class="div-with-20-percent-width-with-margin-10">
						
						<div class="form-group">
							<?php echo form_label('Toll Fee','tollfee'); ?>
							<?php echo form_input(array('name'=>'tollfee','class'=>'form-control tollfee','placeholder'=>'Enter Toll Fee')); ?>
					
						</div>
						<div class="form-group">
							<?php echo form_label('Vehicle Tarriff Amt','vehicletarif'); ?>
							<?php echo form_input(array('name'=>'vehicletarif','class'=>'form-control vehicletarif','placeholder'=>'Amount')); ?>
					
						</div>
						
					</div>
					<!-- col 4 -->
					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
							<?php echo form_label('Total Amount','totalamount'); ?>
							<?php echo form_input(array('name'=>'totalamount','class'=>'form-control totalamount','placeholder'=>'Total Amount')); ?>
					
						</div>
						<div class="form-group">
							<?php echo form_label('Total Tax','totaltax'); ?>
							<?php 				
					$class="form-control taxgroup";
					$tbl="taxes";
			echo $this->form_functions->populate_editable_dropdown('taxgroup',$taxes,$class,$tbl);
			echo form_input(array('name'=>'select_text','id'=>'totaltax','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>		
					
						</div>	
						
					</div>
					
					<!-- hidden -->
					<div class="form-group hide-me">
						<?php echo form_label('Extra Fuel Charge','extrafuel'); ?>
						<?php echo form_input(array('name'=>'extrafuel','class'=>'form-control extrafuel','placeholder'=>'Enter Extra Fuel Charge')); ?>
				
					</div>
					<div class="form-group hide-me">
						<?php $class="form-control";
						$id="tarrif";
						echo form_label('Tariff','triptariflabel'); 
						echo $this->form_functions->populate_dropdown('tariff',$tariffs='',$tariff='',$class,$id,$msg="Tariffs");?>
						<span class="tariff-error text-red"></span>
					</div>
					<div class="form-group hide-me">
						<?php echo form_label('Addt KM Rate','adtkmamount'); ?>
						<?php echo form_input(array('name'=>'adtkmamount','class'=>'form-control adtkmamount','placeholder'=>'Additional KM Amount')); 
						?>
					</div>

					<div class="form-group hide-me">
						<?php echo form_label('KM/HRs','kmhr'); ?>
						<?php echo form_input(array('name'=>'kmhr','class'=>'form-control kmhr','placeholder'=>'KM/HRs')); 
						?>
					</div>

				</div>
				<!-- second section ends -->

				

				<!-- controls  -->
				<div class="row-source-100-percent-width-with-margin-8">
			   		<div class="box-footer">
					<?php 
						echo form_submit("trip-voucher-save","SAVE","class='btn btn-success trip-voucher-save'").nbs(5);  ?>
					<button class='btn btn-danger modal-close' type='button'>CLOSE</button>  
					</div>
				</div>
			
			</fieldset>
		</div><!-- body -->

		</div>
	</div>
    <!-- end loading -->
</div>	
</div>

