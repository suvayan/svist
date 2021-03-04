<?php
	$ftype = trim($prog[0]->feetype);
?>
<div class="card">
	<div class="card-header card-header-info card-header-text">
		<div class="card-text">
			<h4 class="card-title">Student List under: <?php echo trim($prog[0]->title).' - ('.trim($prog[0]->yearnm).')'; ?></h4>
		</div>
	</div>
	<div class="card-body">
		<form id="frmFtApprove">
			<div class="d-flex">
				<button class="btn btn-success btn-sm mr-2" id="fla" disabled>Final Approval</button>
				<button class="btn btn-warning btn-sm mr-2" id="raf" disabled>Reject</button>
			</div>
			<input type="hidden" name="atype" id="atype" value="<?php echo $atype; ?>"/>
			<input type="hidden" name="prog_id2" id="prog_id2" value="<?php echo $prog[0]->id; ?>"/>
			<input type="hidden" name="feetype2" id="feetype2" value="<?php echo ($ftype=='Paid')? 1:0; ?>"/>
			<div class="material-datatables">
				<table class="table table-striped table-no-bordered table-hover datatables" id="tbl_adm" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="5%"><div class="form-check mr-2">
							  <label class="form-check-label">
								<input class="form-check-input" type="checkbox" value="1" name="sfa_all" id="sfa_all" onClick="selectFATuples();">
								<span class="form-check-sign">
								  <span class="check"></span>
								</span>
							  </label>
							</div></th>
							<th width="20%">Name</th>
							<th width="20%">Contact</th>
							<th width="15%">Enrollment</th>
							<th width="15%">Roll</th>
							<th width="15%">Semester</th>
							<th width="5%">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$i=1;
							foreach($adm_list as $afow){
								$aid = $afow->sl;
								echo '<tr>';
								echo '<td>'.$i.'</td>';
								echo '<td>';
								if($afow->approve_flag=='0'){
									echo '<div class="form-check">
									  <label class="form-check-label">
										<input class="form-check-input chk_user2" type="checkbox" value="'.$aid.'" name="acp2[]" id="acp2_'.$i.'">
										<span class="form-check-sign">
										  <span class="check"></span>
										</span>
									  </label>
									</div>';
								}
								echo '<input type="hidden" name="spcid_'.$aid.'" id="spcid_'.$aid.'" value="'.((!empty(${'uroll_'.$i}[0]->spc_id))? ${'uroll_'.$i}[0]->spc_id:'').'"/>
								</td>';
								echo '<td>'.trim($afow->stud_name).'<br>';
								$vf = trim($afow->verification_status);
								echo (($vf=='f')? '<span class="label label-danger">Not Verified</span>':'<span class="label label-success">Verified</span>').'</td>';
								echo '<td>'.trim($afow->email).'<br>'.trim($afow->phone).'</td>';
								//echo '<td><input type="text" name="enroll_'.$aid.'" id="enroll_'.$aid.'" class="form-control"/></td>';
								//echo '<td></td>';
								echo '<td>';
								if(!empty(${'uroll_'.$i}[0]->enrollment_no)){
									echo ${'uroll_'.$i}[0]->enrollment_no;
								}else{
									echo 'NIL';
								}
								echo '</td>';
								echo '<td>';
								if(!empty(${'uroll_'.$i}[0]->roll_no)){
									echo ${'uroll_'.$i}[0]->roll_no;
								}else{
									echo 'NIL';
								}
								echo '</td>';
								echo '<td>'.${'uroll_'.$i}[0]->title.'</td><td>';
								$af = intval(trim($afow->approve_flag));
								if($af==0){
									echo '<span class="label label-warning">Pending</span>';
								}else if($af==1){
									echo '<span class="label label-info">First Approval</span>';
								}else if($af==2){
									echo '<span class="label label-success">Final Approval</span>';
								}else if($af==3){
									echo '<span class="label label-danger">Rejected</span>';
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
	$('#tbl_adm').DataTable({
		"ordering": true,
		"info": true,
		"paging": true,
		"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        "lengthChange": true,
		dom: '1B<"top"lf>rt<"bottom"ip><"clear">',
		buttons: [
			{
				extend: 'excelHtml5',
				title: 'Admission List of <?php echo trim($prog[0]->title).' - '.trim($prog[0]->yearnm); ?>',
				text:'Excel',
				className: 'bg-success text-white mr-2',
				exportOptions: {
					columns: [0, 2, 3, 4, 5, 6, 7]
				}
			},
			{
				extend: 'pdfHtml5',
				title: 'Admission List of <?php echo trim($prog[0]->title).' - '.trim($prog[0]->yearnm); ?>',
				text: 'PDF',
				className: 'bg-success text-white mr-2',
				exportOptions: {
					columns: [0, 2, 3, 4, 5, 6, 7]
				}
			},
			{
				extend: 'print',
				title: 'Admission List of <?php echo trim($prog[0]->title).' - '.trim($prog[0]->yearnm); ?>',
				text: 'Print',
				className: 'bg-success text-white mr-2',
				exportOptions: {
					columns: [0, 2, 3, 4, 5, 6, 7]
				}
			}
		]
	});
	$('[name="tbl_adm_length"]').addClass('browser-default');
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
							url: baseURL+'Admin/finalSelectedStud',
							type: 'POST',
							data: frmData,
							processData: false,
							contentType: false,
							success: (res)=>{
								$('#loading').hide();
								console.log(res)
								if(res=='1')
								{
									getAdmissionList(<?php echo $prog[0]->id; ?>, '<?php echo $atype; ?>');
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
					url: baseURL+'Admin/rejectApprovedSelectedStud',
					type: 'POST',
					data: frmData,
					processData: false,
					contentType: false,
					success: (res)=>{
						$('#loading').hide();
						//console.log(res)
						if(res)
						{
							getAdmissionList(<?php echo $prog[0]->id; ?>, '<?php echo $atype; ?>');
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