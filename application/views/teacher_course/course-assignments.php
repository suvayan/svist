<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title">Assignments</h4>
			  </div>
			  <button class="btn btn-sm btn-info pull-right" onClick="assignmentModal('add', <?php echo $cd[0]->id; ?>, 0);"><i class="material-icons">add</i> Add Assignment</button>
			</div>
			<div class="card-body">
				<ul class="timeline timeline-simple">
					<?php 
						$i=1;
						foreach($cassignment as $caow){ 
						$caid = $caow->sl;
						$atitle = trim($caow->title);
					?>
					<li class="timeline-inverted">
					  <div class="timeline-badge success">
						<?php echo $i; ?>
					  </div>
					  <div class="timeline-panel" id="ca_<?php echo $caid; ?>">
						<div class="timeline-heading">
						  <a href="<?php echo base_url().'Teacher/viewAssignmentDetails/?id='.base64_encode($caid).'&cid='.base64_encode($cd[0]->id).'&prog='.base64_encode($prog_id); ?>">
						  <span class="badge badge-pill badge-info"><?php echo $atitle; ?> - View Details</span>
						  </a>
						  <div class="dropdown pull-right">
							  <a href="javascript:;" class="" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							  </a>
							  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
								  <button type="button" onClick="assignmentModal('edit', <?php echo $cd[0]->id; ?>, <?php echo $caid; ?>);" class="btn btn-success btn-link" data-placement="bottom">
									<i class="material-icons">edit</i> Edit
								  </button>
								  <button type="button" onClick="assignmentFileModal(<?php echo $cd[0]->id; ?>, <?php echo $caid; ?>);" class="btn btn-success btn-link" data-placement="bottom">
									<i class="material-icons">add</i> Add Files
								  </button>
								  <button type="button" onClick="deleteAssignment(<?php echo $caid; ?>, '<?php echo $atitle; ?>');" class="btn btn-danger btn-link" data-placement="bottom">
									<i class="material-icons">delete</i> Delete
								  </button>
							  </div>
							</div>
						</div>
						<div class="timeline-body">
							<h6><?php echo 'Added on '.date('j M Y h:ia',strtotime($caow->add_date)).'<br>Full Marks: '.$caow->marks.'<br>Added By: '.$caow->user_name.'<br>Notified? :'.(($caow->notify!='f')? 'Yes':'No').'<br>Published? :'.(($caow->publish!='f')? 'Yes':'No'); ?></h6>
							<div class="row">
								<?php 
									foreach(${'cafiles'.$i} as $frow){
										$isrc = '';
										$caid = $frow->sl;
										$rtype = trim($frow->file_type);
										$ftype = trim($frow->file_ext);
										if($ftype=='pdf'){
											$isrc = 'fa-file-pdf-o';
										}else if($ftype=='doc' || $ftype=='docx'){
											$isrc = 'fa-file-word-o';
										}else if($ftype=='xls' || $ftype=='xlsx'){
											$isrc = 'fa-file-excel-o';
										}else if($ftype=='jpg'){
											$isrc = 'fa-file-image-o';
										}else{
											$isrc = 'fa-file';
										}
										echo '<div class="col-sm-3 col-xs-3">';
										
										if($rtype=='lk'){
											//echo '<a href="'.$frow->file_name.'" target="_blank"><img src="'.base_url().'assets/img/icons/link.png" style="width:30%;"> View Link</a>';
											echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$caid.'" data-notify="container">
													<i class="material-icons" data-notify="icon">link</i>
													<button type="button" class="close" onClick="deleteAssgnFiles('.$caid.');" aria-label="Close">
													  <i class="material-icons">close</i>
													</button>
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="'.$frow->file_name.'" target="_blank">View Link</a></span>
												</div>';
										}else if($rtype=='fl'){
											//echo '<a href="'.base_url().'uploads/cassignments/'.$frow->file_name.'" target="_blank"><img src="'.base_url().'assets/img/icons/'.$isrc.'.png" style="width:30%;"> Download</a>';
											if(file_exists('./uploads/cassignments/'.$frow->file_name)){
												echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$caid.'" data-notify="container">
													<i class="fa '.$isrc.'" data-notify="icon"></i>
													<button type="button" class="close" onClick="deleteAssgnFiles('.$caid.');" aria-label="Close">
													  <i class="material-icons">close</i>
													</button>
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="'.base_url().'uploads/cassignments/'.$frow->file_name.'" target="_blank"> Download</a></span>
												</div>';
											}
										}
										
										echo '</div>';
									} 
								?>
							</div>
						</div>
					  </div>
					</li>
					<?php $i++; } ?>
				</ul>
			</div>
		</div>
	</div>
	
</div>
<div class="modal fade" id="caModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title" id="caTitle"></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
			<form class="form" method="POST" id="frmAssgn">
			<div class="form-group">
				<input type="hidden" value="<?php echo $prog_id; ?>" name="prog" id="prog">
				<input type="hidden" value="" name="cid" id="cid">
				<input type="hidden" value="" name="assgn_id" id="assgn_id">
				<label for="atitle" class="text-dark">Title*</label>
				<input type="text" class="form-control" name="atitle" id="atitle" minLength="5" required="true">
			</div>
			<div class="form-group">
				<label for="amarks" class="text-dark mb-0">Marks</label>
				<input type="text" class="form-control" name="amarks" id="amarks" required="true" maxLength="3" number="true">
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="sdate" class="text-dark">Start Date</label>
						<input type="datetime-local" class="form-control" name="sdate" id="sdate" required="true">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="ddate" class="text-dark">Deadline</label>
						<input type="datetime-local" class="form-control" name="ddate" id="ddate" required="true">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="adetails" class="text-dark">Description</label><br>
				<textarea class="form-control w-100" cols="80" rows="5" name="adetails" id="adetails" placeholder="**not more than 200 letters" maxLength="200"></textarea>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<div class="togglebutton">
							<label class="text-dark">
							  <input type="checkbox" name="aPublish" id="aPublish" value="1">
							  <span class="toggle"></span>
							  Publish?
							</label>
						  </div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<div class="togglebutton">
							<label class="text-dark">
							  <input type="checkbox" name="anotify" id="anotify" value="1">
							  <span class="toggle"></span>
							  Notify?
							</label>
						  </div>
					</div>
				</div>
			</div>
			<div class="form group">
				<button type="submit" class="btn btn-link pull-right" id="caSubmit"></button>
				<input type="reset" style="visibility:hidden;"/>
				<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</form>
		</div>
		
	  </div>
	</div>
</div>
<!------------------->
<div class="modal fade" id="cafModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Add Assignment Files</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
			<div class="text-center" id="yfl_button">
				<button type="button" onClick="addMore('fl');" class="btn btn-sm btn-info"><i class="material-icons">add</i> Files</button>
				<button type="button" onClick="addMore('lk');" class="btn btn-sm btn-success"><i class="material-icons">add</i> Link</button>
			</div>
			<form class="form" method="POST" enctype="multipart/form-data" id="frmAssgnFiles">
			<div class="form-group">
				<input type="hidden" value="<?php echo $prog_id; ?>" name="fprog" id="fprog">
				<input type="hidden" value="" name="fcid" id="fcid">
				<input type="hidden" value="" name="fassgn_id" id="fassgn_id">
				<input type="hidden" value="0" name="afcount" id="afcount">
			</div>
			
			<span id="cal_fields"></span>

			<div class="form group">
				<button type="submit" class="btn btn-link pull-right">Upload</button>
				<input type="reset" style="visibility:hidden;"/>
				<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</form>
		</div>
		
	  </div>
	</div>
</div>
<!------------------->
<script>
	$('#adetails').summernote();
</script>
<script src="<?php echo base_url().'assets/js/cassignments.js'; ?>"></script>