<?php
	$ftype = trim($prog[0]->feetype);
?>
<ul class="nav nav-pills nav-pills-info nav-justified justify-content-center mt-3" role="tablist">
	<li class="nav-item">
	  <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist">
		Pending List
	  </a>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-toggle="tab" href="#link2" role="tablist">
		First Approval List
	  </a>
	</li>
</ul>
<div class="tab-content tab-space">
	<div class="tab-pane active" id="link1">
		<form id="frmPending" method="POST">
		<div class="d-flex">
			<div class="form-check mr-2">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" value="1" name="spd_all" id="spd_all" onClick="selectAPTuples();"> Select All
				<span class="form-check-sign">
				  <span class="check"></span>
				</span>
			  </label>
			</div>
			<button class="btn btn-info btn-sm mr-2" id="fta" disabled>First Approval</button>
			<button class="btn btn-warning btn-sm mr-2" id="rpl" disabled>Reject</button>
			
		</div>
		<div class="form-group">
			<select name="acayear" id="acayear" class="custom-select w-50">
				<?php
					echo '<option value="">Set academic year for selected</option>';
					if(!empty($ayr)){
						foreach($ayr as $ayow){
							echo '<option value="'.$ayow->sl.'">'.$ayow->yearnm.'</option>';
						}
					}
				?>
			</select>
		</div>
		<input type="hidden" name="prog_id1" id="prog_id1" value="<?php echo $prog[0]->id; ?>"/>
		<input type="hidden" name="feetype1" id="feetype1" value="<?php echo ($ftype=='Paid')? 1:0; ?>"/>
		<div class="material-datatables">
			<table class="table table-striped table-no-bordered table-hover" id="tbl_adm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th width="5%"></th>
						<th width="15%">Name</th>
						<th width="20%">Contact</th>
						<th width="40%">Academic</th>
						<th width="15%">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i=1;
						foreach($adnpd_list as $alow){
							$aid = $alow->sl;
							echo '<tr>';
							echo '<td>'.$i.'</td>';
							echo '<td><div class="form-check mr-2">
							  <label class="form-check-label">
								<input class="form-check-input chk_user1" type="checkbox" value="'.$aid.'" name="acp1[]" id="acp1_'.$i.'">
								<span class="form-check-sign">
								  <span class="check"></span>
								</span>
							  </label>
							</div></td>';
							echo '<td>'.trim($alow->stud_name).'<br>';
							$vf = trim($alow->verification_status);
							echo (($vf=='f')? '<span class="label label-danger">Not Verified</span>':'<span class="label label-success">Verified</span>').'</td>';
							echo '<td>'.trim($alow->email).'<br>'.trim($alow->phone).'<td>';
							if(!empty(${'udaca1_'.$i})){
								echo '<ol type="1">';
								foreach(${'udaca1_'.$i} as $edu){
									echo '<li>'.trim($edu->board).', CGPA/%: '.trim($edu->marks_per);
									if($edu->to!=null){
										echo ', Year: '.date('Y',strtotime($edu->to));
									}
									echo '<br>'.trim($edu->organization).'</li>';
								}
								echo '</ol>';
							}
							echo '</td><td>';
							$af = intval(trim($alow->approve_flag));
							if($af==0){
								echo '<span class="label label-warning">Pending</span>';
							}else if($af==1){
								echo '<span class="label label-info">First Approval</span>';
							}
							echo '</td><tr>';
							$i++;
						}
					?>
				</tbody>
			</table>
		</div>
		</form>
	</div>
	<div class="tab-pane" id="link2">
		<form id="frmFtApprove">
		<div class="d-flex">
			<div class="form-check mr-2">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" value="1" name="sfa_all" id="sfa_all" onClick="selectFATuples();"> Select All
				<span class="form-check-sign">
				  <span class="check"></span>
				</span>
			  </label>
			</div>
			<button class="btn btn-success btn-sm mr-2" id="fla" disabled>Final Approval</button>
			<button class="btn btn-warning btn-sm mr-2" id="raf" disabled>Reject</button>
			<span class="text-danger">Enrollment and Roll are required, else it will not update.</span>
		</div>
		<div class="form-group">
			<select name="semid" id="semid" class="custom-select w-50">
				<?php
					if(!empty($sems)){
						$csems = count($sems);
						if($csems==1){
							echo '<option value="'.$sems[0]->id.'" selected>'.$sems[0]->title.'</option>';
						}else{
							foreach($sems as $ssow){
								echo '<option value="'.$ssow->id.'">'.$ssow->title.'</option>';
							}
						}
					}
				?>
			</select>
		</div>
		<input type="hidden" name="prog_id2" id="prog_id2" value="<?php echo $prog[0]->id; ?>"/>
		<input type="hidden" name="feetype2" id="feetype2" value="<?php echo ($ftype=='Paid')? 1:0; ?>"/>
		<div class="material-datatables">
			<table class="table table-striped table-no-bordered table-hover" id="tbl_adm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th width="5%"></th>
						<th width="10%">Name</th>
						<th width="20%">Contact</th>
						<th width="15%">Enrollment</th>
						<th width="15%">Roll</th>
						<th width="15%">Payment</th>
						<th width="15%">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i=1;
						foreach($adnfa_list as $afow){
							$aid = $afow->sl;
							echo '<tr>';
							echo '<td>'.$i.'</td>';
							echo '<td><div class="form-check mr-2">
							  <label class="form-check-label">
								<input class="form-check-input chk_user2" type="checkbox" value="'.$aid.'" name="acp2[]" id="acp2_'.$i.'">
								<span class="form-check-sign">
								  <span class="check"></span>
								</span>
							  </label>
							</div>
							<input type="hidden" name="spcid_'.$aid.'" id="spcid_'.$aid.'" value="'.$afow->spc_id.'"/>
							</td>';
							echo '<td>'.trim($afow->stud_name).'<br>';
							$vf = trim($afow->verification_status);
							echo (($vf=='f')? '<span class="label label-danger">Not Verified</span>':'<span class="label label-success">Verified</span>').'</td>';
							echo '<td>'.trim($afow->email).'<br>'.trim($afow->phone).'</td>';
							echo '<td><input type="text" name="enroll_'.$aid.'" id="enroll_'.$aid.'" class="form-control"/></td>';
							echo '<td><input type="number" name="roll_'.$aid.'" id="roll_'.$aid.'" class="form-control"/></td>';
							echo '<td>';
							if($ftype=='Paid'){
								echo ($afow->payment_status=='f')? 'Payment Pending' : 'Payment Done';
							}else{
								echo 'Free';
							}
							echo '</td><td>';
							$af = intval(trim($afow->approve_flag));
							if($af==0){
								echo '<span class="label label-warning">Pending</span>';
							}else if($af==1){
								echo '<span class="label label-info">First Approval</span>';
							}
							echo '</td></tr>';
							$i++;
						}
					?>
				</tbody>
			</table>
		</div>
		</form>
	</div>
</div>

<script>
	/**********************PENDING LIST*********************/
	function selectAPTuples()
	{
		var checked = $('#spd_all').is(':checked');
		if ($('.chk_user1').length > 0){
			if(checked){
			   $('.chk_user1').each(function() {
				  $('.chk_user1').prop('checked',true);
			   });
			   $('#fta').removeAttr('disabled');
			   $('#rpl').removeAttr('disabled');
			 }else{
			   $('.chk_user1').each(function() {
				 $('.chk_user1').prop('checked',false);
			   });
			   $('#fta').attr('disabled', true);
			   $('#rpl').attr('disabled', true);
			 } 
		}
	}
	$('.chk_user1').on('change', ()=>{
		if($('.chk_user1').is(':checked')){
			$('#fta').removeAttr('disabled');
			$('#rpl').removeAttr('disabled');
		}else{
			$('#fta').attr('disabled', true);
			$('#rpl').attr('disabled', true);
		}
	});
	$('#fta').on('click', (e)=>{
		e.preventDefault();
		var ay = $('#acayear').val();
		var i=1;
		var j=0;
		$('.chk_user1').each(function() {
		  if($('#acp1_'+i).is(':checked')){
			  j++;
		  } 
		  i++;
	   });
		if(ay!=""){
			if(j!=0){
				var frmData = new FormData($('#frmPending')[0]);
				swal({
					title: 'Are you sure?',
					text: "You want to change status to First Approval for selected student(s)",
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					confirmButtonText: 'Yes, Approve it!',
					buttonsStyling: false
				}).then(function(result) {
					if(result.value) {
						$('#loading').show();
						$.ajax({
							url: baseURL+'Teacher/firstSelectedStud',
							type: 'POST',
							data: frmData,
							processData: false,
							contentType: false,
							success: (res)=>{
								$('#loading').hide();
								//console.log(res)
								if(res)
								{
									$('#adm_list').html("");
									$.notify({icon:"add_alert",message:'The '+j+' student(s) has been selected for first approval.'},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})
								}else{
									$.notify({icon:"add_alert",message:'Something went worng.'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
								}
							}
						})
					}
				});
			}else{
				$.notify({icon:"add_alert",message:'No student has been selected'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
			}
		}else{
			$.notify({icon:"add_alert",message:'Academic Year not set'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
		}
	});
	$('#rpl').on('click', ()=>{
		e.preventDefault();
		var cb = [];
		var i=1;
		var j=0;
		$('.chk_user1').each(function() {
		  if($('#acp1_'+i).is(':checked')){
			  j++;
		  } 
		  i++;
	   });
	   var frmData = new FormData($('#frmPending')[0]);
		swal({
			title: 'Are you sure?',
			text: "You want to reject selected student(s)",
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			confirmButtonText: 'Yes, Approve it!',
			buttonsStyling: false
		}).then(function(result) {
			if(result.value) {
				$('#loading').show();
				$.ajax({
					url: baseURL+'Teacher/rejectPendingSelectedStud',
					type: 'POST',
					data: frmData,
					processData: false,
					contentType: false,
					success: (res)=>{
						$('#loading').hide();
						//console.log(res)
						if(res)
						{
							$('#adm_list').html("");
							$.notify({icon:"add_alert",message:'The '+j+' student(s) has been rejected.'},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})
						}else{
							$.notify({icon:"add_alert",message:'Something went worng.'},{type:'warning',timer:3e3,placement:{from:'top',align:'right'}})
						}
					}
				})
			}
		});
	})
	/********************************************************/
	/**********************APPROVE LIST**********************/
	function selectFATuples()
	{
		var checked = $('#sfa_all').is(':checked');
		if ($('.chk_user2').length > 0){
			if(checked){
			   $('.chk_user2').each(function() {
				  $('.chk_user2').prop('checked',true);
			   });
			   $('#fla').removeAttr('disabled');
			   $('#raf').removeAttr('disabled');
			 }else{
			   $('.chk_user2').each(function() {
				 $('.chk_user2').prop('checked',false);
			   });
			   $('#fla').attr('disabled', true);
			   $('#raf').attr('disabled', true);
			 } 
		}
	}
	$('.chk_user2').on('change', ()=>{
		if($('.chk_user2').is(':checked')){
			$('#fla').removeAttr('disabled');
			$('#raf').removeAttr('disabled');
		}else{
			$('#fla').attr('disabled', true);
			$('#raf').attr('disabled', true);
		}
	});
	
	$('#fla').on('click', (e)=>{
		e.preventDefault();
		var i=1;
		var j=0;
		$('.chk_user2').each(function() {
		  if($('#acp2_'+i).is(':checked')){
			  j++;
		  } 
		  i++;
	   });
	   if(j!=0){
		   var frmData = new FormData($('#frmFtApprove')[0]);
		   swal({
					title: 'Are you sure?',
					text: "You want to change status to Final Approval for selected student(s)",
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					confirmButtonText: 'Yes, Approve it!',
					buttonsStyling: false
				}).then(function(result) {
					if(result.value) {
						$('#loading').show();
						$.ajax({
							url: baseURL+'Teacher/finalSelectedStud',
							type: 'POST',
							data: frmData,
							processData: false,
							contentType: false,
							success: (res)=>{
								$('#loading').hide();
								//console.log(res)
								if(res=='1')
								{
									$('#adm_list').html("");
									$.notify({icon:"add_alert",message:'The '+j+' student(s) has been selected for final approval.'},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})
								}else if(res=='0'){
									$.notify({icon:"add_alert",message:'Something went worng.'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
								}else if(res=='2'){
									$.notify({icon:"add_alert",message:'Courses are not added/not linked with semesters'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
								}
							}
						})
					}
				});
	   }else{
		   $.notify({icon:"add_alert",message:'No student has been selected'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
	   }
	})
	$('#raf').on('click', ()=>{
		e.preventDefault();
		var cb = [];
		var i=1;
		var j=0;
		$('.chk_user2').each(function() {
		  if($('#acp2_'+i).is(':checked')){
			  j++;
		  } 
		  i++;
	   });
	   var frmData = new FormData($('#frmFtApprove')[0]);
		swal({
			title: 'Are you sure?',
			text: "You want to reject selected student(s)",
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			confirmButtonText: 'Yes, Approve it!',
			buttonsStyling: false
		}).then(function(result) {
			if(result.value) {
				$('#loading').show();
				$.ajax({
					url: baseURL+'Teacher/rejectApprovedSelectedStud',
					type: 'POST',
					data: frmData,
					processData: false,
					contentType: false,
					success: (res)=>{
						$('#loading').hide();
						//console.log(res)
						if(res)
						{
							$('#adm_list').html("");
							$.notify({icon:"add_alert",message:'The '+j+' student(s) has been rejected.'},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})
						}else{
							$.notify({icon:"add_alert",message:'Something went worng.'},{type:'warning',timer:3e3,placement:{from:'top',align:'right'}})
						}
					}
				})
			}
		});
	});
	/********************************************************/
</script>