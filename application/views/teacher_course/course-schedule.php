<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title"><?php echo $title; ?></h4>
				
			  </div>
			  <button class="btn btn-link btn-primary pull-right" data-toggle="modal" data-target="#schedule"><i class="material-icons">add</i> Schedule class for this course</button>
			</div>
			<div class="card-body">
				<div class="material-datatables">
					<table class="table table-striped table-no-bordered table-hover" id="schclassList" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="5%">Sl.</th>
								<th width="40%">Title</th>
								<th width="30%">Duration</th>
								<th width="25%">Medium</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach($schClass as $schr){ ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo '<div class="td-name">'.$schr->class_title.'<br>'.$schr->class_type.'</div>'; ?></td>
									<td><?php echo 'Start: '.date('j M Y h:sa',strtotime($schr->start_datetime)).'<br>End: '.date('j M Y h:sa',strtotime($schr->end_datetime)); ?></td>
									<td><?php echo $schr->onlinemedium; ?></td>
								</tr>
							<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Schedule Class</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmSchedule">
			<div class="modal-body">
				<div class="form-group">
					<label for="schtitle" class="text-dark">Title</label>
					<input type="text" class="form-control" name="schtitle" id="schtitle" required="true">
					<input type="hidden" value="<?php echo $prog_id; ?>" name="sch_prog" id="sch_prog">
					<input type="hidden" value="<?php echo $cd[0]->id; ?>" name="sch_course" id="sch_course">
				</div>
				  <div class="form-group">
					<label for="sch_start" class="text-dark">Start Date Time</label>
					<input type="datetime-local" class="form-control" name="sch_start" id="sch_start" required="true">
				  </div>
				  <div class="form-group">
					<label for="sch_end" class="text-dark">End Date Time</label>
					<input type="datetime-local" class="form-control" name="sch_end" id="sch_end" required="true">
				  </div>
				  <div class="form group">
						<div class="togglebutton">
							<label class="text-dark">
							  Offline
							  <input type="checkbox" name="schLine" id="schLine" checked="true" value="1">
							  <span class="toggle"></span>
							  Online
							</label>
						  </div>
				  </div>
				  <div class="form-group">
					<label for="sch_online" class="text-dark">Online Link</label><br>
					<textarea class="form-control" name="sch_online" id="sch_online"></textarea>
				  </div>
				  <div class="form group">
						<div class="togglebutton">
							<label class="text-dark">
							  Notify?
							  <input type="checkbox" name="schNotify" id="schNotify" checked="true" value="1">
							  <span class="toggle"></span>
							</label>
						  </div>
				  </div>
			</div>
			<div class="modal-footer">
				<input type="reset" style="visibility:hidden">
			  <button type="submit" class="btn btn-link">Save</button>
			  
			  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#schclassList').DataTable();
	$('#frmSchedule').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			e.preventDefault();
			var frmSchData = new FormData($('#frmSchedule')[0]);
			$.ajax({
				url: baseURL+'Teacher/addScheduleClass',
				type: 'POST',
				data: frmSchData,
				cache : false,
				processData: false,
				contentType: false,
				async: false,
				success: (res)=>{ 
					$('#schedule').modal('hide');
					$('#frmSchedule')[0].reset();
					$('#loading').css('display', 'none');
					var obj = JSON.parse(res);
					//console.log(obj)
					swal(
					  'Class',
					  obj.msg,
					  obj.status
					).then(result=>{
						getBtnContent(<?php echo trim("'schedule', ".$cd[0]->id.", ".$prog_id); ?>);
					});
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
</script>