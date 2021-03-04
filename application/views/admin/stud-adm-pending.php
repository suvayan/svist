
<?php
	$ftype = trim($prog[0]->feetype);
?>
<div class="card">
	<div class="card-header card-header-info card-header-text">
		<div class="card-text">
			<h4 class="card-title">Student List under: <?php echo trim($prog[0]->title).' - '.trim($prog[0]->yearnm); ?></h4>
		</div>
	</div>
	<div class="card-body">
		<form id="frmPending" method="POST">
			<div class="d-flex w-100">
				<div class="form-group">
					<select name="acayear" id="acayear" class="custom-select mr-2">
						<?php
							echo '<option value="">Set academic year</option>';
							if(!empty($ayr)){
								foreach($ayr as $ayow){
									echo '<option value="'.$ayow->sl.'">'.$ayow->yearnm.'</option>';
								}
							}
						?>
					</select>
				</div>
				<button class="btn btn-info btn-sm mr-2" id="fta" disabled>First Approval</button>
				<button class="btn btn-warning btn-sm mr-2" id="rpl" disabled>Reject</button>
				
			</div>
			
			<input type="hidden" name="prog_id1" id="prog_id1" value="<?php echo $prog[0]->id; ?>"/>
			<input type="hidden" name="feetype1" id="feetype1" value="<?php echo ($ftype=='Paid')? 1:0; ?>"/>
			<div class="material-datatables">
				<table class="table table-striped table-no-bordered table-hover" id="tbl_pre" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="5%">
								<div class="form-check mr-2">
								  <label class="form-check-label">
									<input class="form-check-input" type="checkbox" value="1" name="spd_all" id="spd_all" onClick="selectAPTuples();">
									<span class="form-check-sign">
									  <span class="check"></span>
									</span>
								  </label>
								</div>
							</th>
							<th width="15%">Name</th>
							<th width="15%">Contact</th>
							<th width="35%">Academic</th>
							<th width="10%">Apply Date</th>
							<th width="15%">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=1;
							foreach($adm_list as $alow){
								$aid = $alow->sl;
								echo '<tr>';
								echo '<td>'.$i.'</td>';
								echo '<td>';
								if($alow->approve_flag=='0'){
									echo '<div class="form-check">
									  <label class="form-check-label">
										<input class="form-check-input chk_user1" type="checkbox" value="'.$aid.'" name="acp1[]" id="acp1_'.$i.'">
										<span class="form-check-sign">
										  <span class="check"></span>
										</span>
									  </label>
									</div>';
								}
								echo '</td>';
								echo '<td>'.trim($alow->stud_name).'<br>';
								$vf = trim($alow->verification_status);
								echo (($vf=='f')? '<span class="label label-danger">Not Verified</span>':'<span class="label label-success">Verified</span>').'</td>';
								echo '<td>'.trim($alow->email).'<br>'.trim($alow->phone).'</td>';
								echo '<td>';
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
								echo '</td>';
								echo '<td>'.date('j M Y h:ia',strtotime($alow->apply_datetime)).'</td>';
								echo '<td>';
								$af = intval(trim($alow->approve_flag));
								if($af==0){
									echo '<span class="label label-warning">Pending</span>';
								}else if($af==1){
									echo '<span class="label label-info">First Approval</span>';
								}
								else if($af==2){
									echo '<span class="label label-success">Final Approval</span>';
								}else if($af==3){
									echo '<span class="label label-danger">Rejected</span>';
								}
								echo '</td>';
								echo '</tr>';
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
	$('#tbl_pre').DataTable({
		"ordering": true,
		"info": true,
		"paging": true,
		"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        "lengthChange": true,
		dom: '1B<"top"lf>rt<"bottom"ip><"clear">',
		buttons: [
			{
				extend: 'excelHtml5',
				title: 'Pre Admission List of <?php echo trim($prog[0]->title).' - '.trim($prog[0]->yearnm); ?>',
				text:'Excel',
				className: 'bg-success text-white mr-2',
				exportOptions: {
					columns: [0, 2, 3, 4, 5, 6]
				}
			},
			{
				extend: 'pdfHtml5',
				title: 'Pre Admission List of <?php echo trim($prog[0]->title).' - '.trim($prog[0]->yearnm); ?>',
				text: 'PDF',
				className: 'bg-success text-white mr-2',
				exportOptions: {
					columns: [0, 2, 3, 4, 5, 6]
				}
			},
			{
				extend: 'print',
				title: 'Pre Admission List of <?php echo trim($prog[0]->title).' - '.trim($prog[0]->yearnm); ?>',
				text: 'Print',
				className: 'bg-success text-white mr-2',
				exportOptions: {
					columns: [0, 2, 3, 4, 5, 6]
				}
			}
		]
	});
	$('[name="tbl_pre_length"]').addClass('browser-default');

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
							url: baseURL+'Admin/firstSelectedStud',
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
					url: baseURL+'Admin/rejectPendingSelectedStud',
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
	})
	/********************************************************/
</script>