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
		
			<input type="hidden" name="prog_id2" id="prog_id2" value="<?php echo $prog[0]->id; ?>"/>
			<div class="material-datatables">
				<table class="table table-striped table-no-bordered table-hover datatables" id="tbl_adm" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="20%">Name</th>
							<th width="10%">Enrollment</th>
							<th width="10%">Semester</th>
							<th width="20%">Added Courses</th>
							<th width="35%">New Courses</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$i=1;
							foreach($studs as $afow){
								echo '<tr id="row_'.$i.'">';
								echo '<td>'.$i.'</td>';
								echo '<td>'.trim($afow->name).'</td>';
								echo '<td>';
								if(!empty($afow->enrollment_no)){
									echo $afow->enrollment_no;
								}else{
									echo 'NIL';
								}
								echo '</td>';
								echo '<td>'.$afow->title.'</td><td>';
								if(!empty(${'oldcourse_'.$i})){
									echo '<ol type="1">';
									foreach(${'oldcourse_'.$i} as $scow){
										echo '<li>'.$scow->title.' ('.trim($scow->type).')</li>';
									}
									echo '</ol>';
								}else{
									echo 'No course added.';
								}
								echo '</td><td>Courses under semester: '.$afow->title;
								
								if(!empty(${'newcourse_'.$i})){
									echo '<form id="frmCourse_'.$i.'" action="javascript:saveStudCourse('.$i.');">';
									echo '<input type="hidden" name="sps_'.$i.'" id="sps_'.$i.'" value="'.$afow->sps_id.'"/>';
									echo '<ol type="1">';
									foreach(${'newcourse_'.$i} as $scow){
										echo '<li><input type="checkbox" value="'.$scow->id.'" name="scourse_'.$i.'[]" id="scoursescourse_'.$i.'_'.$scow->id.'"> '.$scow->title.' ('.trim($scow->type).')</li>';
									}
									echo '</ol><button class="btn btn-sm btn-success">Save</button>';
									echo '</form>';
								}else{
									echo '<br>No courses to add.';
								}
								echo '</td></tr>';
								$i++;
							}
						?>
					</tbody>
				</table>
			</div>
		
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
	
	function saveStudCourse(id)
	{
		var pid = $('#prog_id2').val();
		var frmdata = new FormData($('#frmCourse_'+id)[0]);
		frmdata.append('rowId', id);
		swal({
			title: 'Are you sure?',
			text: "You want to add courses tos selected student(s)",
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			confirmButtonText: 'Yes, Add them!',
			buttonsStyling: false
		}).then(function(result) {
			if(result.value) {
				$('#loading').show();
				$.ajax({
					url: baseURL+'Admin/addStudCourses',
					type: 'POST',
					data: frmdata,
					processData: false,
					contentType: false,
					success: (res)=>{
						$('#loading').hide();
						console.log(res)
						if(res){
							getStudCoursesList(pid);
							$.notify({icon:"add_alert",message:'The courses has been added successfully.'},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})
						}else{
							$.notify({icon:"add_alert",message:'Courses are not added/not linked with semesters'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
						}
					}
				})
			}
		});
	}
</script>