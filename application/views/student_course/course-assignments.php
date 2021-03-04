<div class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-md-12">
				<div class="card mt-2" style="background-color: #e6ecf6;">
					<div class="card-header card-header-info card-header-text">
					  <div class="card-text">
						<h4 class="card-title">Assignments</h4>
					  </div>
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
							  <div class="timeline-panel">
								<div class="timeline-heading">
								  <a href="<?php echo base_url().'Student/viewAssignmentDetails/?id='.base64_encode($caid).'&cid='.base64_encode($cd[0]->id).'&prog='.base64_encode($prog_id); ?>">
								  <span class="badge badge-pill badge-info"><?php echo $atitle; ?></span>
								  </a>
								</div>
								<div class="timeline-body">
									<h6><?php echo 'Added on '.date('j M Y',strtotime($caow->add_date)).'<br>Full Marks: '.$caow->marks.'<br>Added By: '.$caow->user_name; ?></h6>
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
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="'.$frow->file_name.'" target="_blank">View Link</a></span>
												</div>';
										}else if($rtype=='fl'){
											//echo '<a href="'.base_url().'uploads/cassignments/'.$frow->file_name.'" target="_blank"><img src="'.base_url().'assets/img/icons/'.$isrc.'.png" style="width:30%;"> Download</a>';
											if(file_exists('./uploads/cassignments/'.$frow->file_name)){
												echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$caid.'" data-notify="container">
													<i class="fa '.$isrc.'" data-notify="icon"></i>
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
	</div>
</div>
<!------------------->
<script src="<?php echo base_url().'assets/js/cassignments.js'; ?>"></script>