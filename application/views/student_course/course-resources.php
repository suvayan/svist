<div class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-md-12">
				<div class="card mt-2" style="background-color: #e6ecf6;">
					<div class="card-header card-header-info card-header-text">
					  <div class="card-text">
						<h4 class="card-title">Resources</h4>
					  </div>
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
										</h4>
										<h6 class="text-center"><?php echo 'Added on '.date('j M Y',strtotime($crow->add_date)).'<br>Added By: '.$crow->user_name; ?></h6>
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
												echo '<div class="col-sm-6 mb-3">';
												if($rtype=='yt'){
													//echo '<a href="javascript:;" onClick="openYTModal('.$cfid.')"><img src="'.base_url().'assets/img/icons/youtube.png" style="width:30%;"> View Video</a>';
													echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$cfid.'" data-notify="container">
															<i class="fa fa-youtube-play" data-notify="icon"></i>
															<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
															<span data-notify="message"><a href="javascript:;" onClick="openYTModal('.$cfid.')"> View Video</a></span>
														</div>';
												}else if($rtype=='lk'){
													//echo '<a href="'.$frow->linkfile.'" target="_blank"><img src="'.base_url().'assets/img/icons/link.png" style="width:30%;"> View Link</a>';
													echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$cfid.'" data-notify="container">
															<i class="material-icons" data-notify="icon">link</i>
															<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
															<span data-notify="message"><a href="'.$frow->linkfile.'" target="_blank">View Link</a></span>
														</div>';
												}else if($rtype=='fl'){
													//echo '<a href="javascript:;" onClick="getResFile('.$cfid.')"><img src="'.base_url().'assets/img/icons/'.$isrc.'.png" style="width:30%;"> Download</a>';
													echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$cfid.'" data-notify="container">
															<i class="fa '.$isrc.'" data-notify="icon"></i>
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