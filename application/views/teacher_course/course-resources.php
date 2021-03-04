
<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title">Resources</h4>
			  </div>
			  <button class="btn btn-sm btn-info pull-right" onClick="resourceModal('add', <?php echo $cd[0]->id; ?>, 0);"><i class="material-icons">add</i> Add Resource</button>
			</div>
			<div class="card-body">
				<div class="row">
					<?php 
						$i=1;
						foreach($cresource as $crow){ 
						$crid = $crow->sl;
						//$ltype = trim($crow->file_type);
						$rtitle = trim($crow->title);
					?>
					<div class="col-sm-6" id="cr_<?php echo $crid; ?>">
						<div class="card">
						  <div class="card-header">
							<div class="card-text">
								<h4 class="card-title text-center"> 
									<?php echo $rtitle; ?>
									<div class="dropdown pull-right">
									  <a href="javascript:;" class="" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="material-icons">more_vert</i>
									  </a>
									  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
										  <button type="button" onClick="resourceModal('edit', <?php echo $cd[0]->id; ?>, <?php echo $crid; ?>);" class="btn btn-success btn-link" data-placement="bottom">
											<i class="material-icons">edit</i> Edit
										  </button>
										  <button type="button" onClick="resourceFileModal(<?php echo $cd[0]->id; ?>, <?php echo $crid; ?>);" class="btn btn-success btn-link" data-placement="bottom">
											<i class="material-icons">add</i> Add Files
										  </button>
										  <button type="button" onClick="deleteResource(<?php echo $crid; ?>, '<?php echo $rtitle; ?>');" class="btn btn-danger btn-link" data-placement="bottom">
											<i class="material-icons">delete</i> Delete
										   </button>
									  </div>
									</div>
								</h4>
								<h6 class="text-center"><?php echo 'Added on '.date('j M Y',strtotime($crow->add_date)).'<br>Added By: '.$crow->user_name.'<br>Notified?: '.((trim($crow->notify)!='f')? 'Yes':'No'); ?></h6>
							</div>
						  </div>
						  <div class="card-body">
							<div class="row">
								<?php 
									foreach(${'crfiles'.$i} as $frow){
										$isrc = '';
										$cfid = $frow->sl;
										$rtype = trim($frow->type);
										$ftype = trim($frow->file_type);
										if($ftype=='pdf'){
											$isrc = 'fa-file-pdf-o';
										}else if($ftype=='doc' || $ftype=='docx'){
											$isrc = 'fa-file-word-o';
										}else if($ftype=='xls' || $ftype=='xlsx'){
											$isrc = 'fa-file-excel-o';
										}else if($ftype=='jpg'){
											$isrc = 'fa-file-image-o';
										}else if($ftype=='mp4'){
											$isrc = 'fa-file-video-o';
										}else{
											$isrc = 'fa-file';
										}
										echo '<div class="col-sm-6">';
										
										if($rtype=='yt'){
											echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$cfid.'" data-notify="container">
													<i class="fa fa-youtube-play" data-notify="icon"></i>
													<button type="button" class="close" onClick="deleteResFiles('.$cfid.');" aria-label="Close">
													  <i class="material-icons">close</i>
													</button>
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="javascript:;" onClick="openYTModal('.$cfid.')"> View Video</a></span>
												</div>';
										}else if($rtype=='lk'){
											echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$cfid.'" data-notify="container">
													<i class="material-icons" data-notify="icon">link</i>
													<button type="button" class="close" onClick="deleteResFiles('.$cfid.');" aria-label="Close">
													  <i class="material-icons">close</i>
													</button>
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="'.$frow->linkfile.'" target="_blank">View Link</a></span>
												</div>';
										}else if($rtype=='fl'){
											echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$cfid.'" data-notify="container">
													<i class="fa '.$isrc.'" data-notify="icon"></i>
													<button type="button" class="close" onClick="deleteResFiles('.$cfid.');" aria-label="Close">
													  <i class="material-icons">close</i>
													</button>
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="javascript:;" onClick="getResFile('.$cfid.')"> Download</a></span>
												</div>';
										}
										
										echo '</div>';
									} 
								?>
							</div>
						  </div>
						</div>
					</div>
					<?php $i++; } ?>
				</div>
			</div>
		</div>
	</div>
	
</div>
<div class="modal fade" id="crModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title" id="crTitle"></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
			<form class="form" method="POST" id="frmRes">
			<div class="form-group">
				<input type="hidden" value="<?php echo $prog_id; ?>" name="prog" id="prog">
				<input type="hidden" value="" name="cid" id="cid">
				<input type="hidden" value="" name="res_id" id="res_id">
				<label for="rtitle" class="text-dark">Title*</label>
				<input type="text" class="form-control" name="rtitle" id="rtitle" minLength="5" required="true">
			</div>
			<div class="form-group">
				<label for="rdetails" class="text-dark">Description</label><br>
				<textarea class="form-control w-100" cols="80" rows="5" name="rdetails" id="rdetails" placeholder="**not more than 200 letters" maxLength="200"></textarea>
			</div>
			<div class="form-group">
				<div class="togglebutton">
					<label class="text-dark">
					  <input type="checkbox" name="rnotify" id="rnotify" value="1">
					  <span class="toggle"></span>
					  Notify?
					</label>
				  </div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-link pull-right" id="crSubmit"></button>
				<input type="reset" style="visibility:hidden;"/>
				<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</form>
		</div>
		
	  </div>
	</div>
</div>
<!------------------->
<div class="modal fade" id="crfModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Add Resource Files</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
			<div class="text-center" id="yfl_button">
				<button type="button" onClick="addMore('yt');" class="btn btn-sm btn-danger"><i class="material-icons">add</i> YouTube</button>
				<button type="button" onClick="addMore('fl');" class="btn btn-sm btn-info"><i class="material-icons">add</i> Files/Video</button>
				<button type="button" onClick="addMore('lk');" class="btn btn-sm btn-success"><i class="material-icons">add</i> Link</button>
			</div>
			<form class="form" method="POST" enctype="multipart/form-data" id="frmResFiles">
			<div class="form-group">
				<input type="hidden" value="<?php echo $prog_id; ?>" name="fprog" id="fprog">
				<input type="hidden" value="" name="fcid" id="fcid">
				<input type="hidden" value="" name="fres_id" id="fres_id">
				<input type="hidden" value="0" name="rfcount" id="rfcount">
			</div>
			
			<span id="crl_fields"></span>

			<div class="form group">
				<button type="submit" class="btn btn-link pull-right">Upload</button>
				<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</form>
		</div>
		
	  </div>
	</div>
</div>
<!------------------->
<div class="modal fade" id="YTModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Resource Youtube/Video Preview</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body" id="ytbody">
		  
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
		</div>
	  </div>
	</div>
</div>
<script src="<?php echo base_url().'assets/js/cresources.js'; ?>"></script>
<script>
	$('#rdetails').summernote();
</script>
