<div class="content">
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<div class="card mt-2" style="background-color: #e6ecf6;">
					<div class="card-header card-header-info card-header-text">
					  <div class="card-text">
						<h4 class="card-title">Lectures</h4>
					  </div>
					</div>
					<div class="card-body">
						<div class="row">
							<?php 
								foreach($clectures as $clow){ 
								$clid = $clow->sl;
								$ltype = trim($clow->file_type);
								$ltitle = trim($clow->title);
							?>
							<div class="col-sm-4" id="cl_<?php echo $clid; ?>">
								<div class="card">
								  <div class="card-header">
									<div class="card-text">
										<h4 class="card-title text-center"> 
											<?php 
												
												if($ltype=='fl'){
													echo '<i class="material-icons text-info" style="font-size:5rem;">description</i>';
												}else if($ltype=='yt'){
													echo '<i class="fa fa-youtube-play text-danger" style="font-size:5rem;"></i>';
												}else if($ltype=='lk'){
													echo '<i class="material-icons text-success" style="font-size:5rem;">link</i>';
												}
											?>
										</h4>
										
								    </div>
								  </div>
								  <div class="card-body">
									<h5 class="text-center">
										<?php
											if($ltype=='fl'){
												echo '<a href="javascript:;" onClick="getLecFile('.$clid.')">'.$ltitle.'</a>';
											}else if($ltype=='yt'){
												echo '<a href="javascript:;" onClick="openYTModal('.$clid.')">'.$ltitle.'</a>';
											}else if($ltype=='lk'){
												echo '<a href="'.$clow->link.'" target="_blank">'.$ltitle.'</a>';
											}
										?>
									</h5>
									<h6>
										<?php
											echo 'Added on: '.date('j M Y',strtotime($clow->add_date)).'<br>Added By: '.$clow->user_name;
										?>
									</h6>
								  </div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="YTModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
	<div class="modal-header">
	  <h4 class="modal-title">Lecture Youtube Video Preview</h4>
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
<script src="<?php echo base_url().'assets/js/clectures.js'; ?>"></script>