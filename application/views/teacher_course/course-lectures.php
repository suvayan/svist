
<div class="row">
			
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title">Lectures</h4>
			  </div>
			  <button class="btn btn-sm btn-info pull-right" onClick="lectureModal('add', <?php echo $cd[0]->id; ?>, 0);"><i class="material-icons">add</i> Add Lecture</button>
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
									<div class="dropdown pull-right">
									  <a href="javascript:;" class="" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="material-icons">more_vert</i>
									  </a>
									  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
										  <button type="button" onClick="lectureModal('edit', <?php echo $cd[0]->id; ?>, <?php echo $clid; ?>);" class="btn btn-success btn-link" data-placement="bottom">
											<i class="material-icons">edit</i> Edit
										  </button>
										  <button type="button" onClick="deleteLecture(<?php echo $clid; ?>, '<?php echo $ltitle; ?>');" class="btn btn-danger btn-link" data-placement="bottom">
											<i class="material-icons">delete</i> Delete
										   </button>
									  </div>
									</div>
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
									//'Lecture Date: '.date('j M Y',strtotime($clow->lec_date)).
									echo 'Added on: '.date('j M Y h:ia',strtotime($clow->add_date)).'<br>Added By: '.$clow->user_name;
									echo '<br>Notified?: '.(($clow->notify!='f')? 'Yes':'No');
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
<div class="modal fade" id="clModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title" id="clTitle"></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
			<form class="form" method="POST" action="" enctype="multipart/form-data" id="frmLec">
			<div class="form-group">
				<input type="hidden" value="<?php echo $prog_id; ?>" name="prog" id="prog">
				<input type="hidden" value="" name="cid" id="cid">
				<input type="hidden" value="" name="lec_id" id="lec_id">
				<label for="ltitle" class="text-dark">Title*</label>
				<input type="text" class="form-control" name="ltitle" id="ltitle" minLength="5" required="true">
			</div>
			<div class="form-group">
				<div class="checkbox-radios">
					<div class="form-check form-check-inline">
					  <label class="form-check-label text-dark">
						<input class="form-check-input" type="radio" value="fl" name="ltype" id="fl"  required="true"> File*
						<span class="form-check-sign">
						  <span class="check"></span>
						</span>
					  </label>
					</div>
					<div class="form-check form-check-inline">
					  <label class="form-check-label text-dark">
						<input class="form-check-input" type="radio" value="lk" name="ltype" id="lk"  required="true"> Link*
						<span class="form-check-sign">
						  <span class="check"></span>
						</span>
					  </label>
					</div>
					<div class="form-check form-check-inline">
					  <label class="form-check-label text-dark">
						<input class="form-check-input" type="radio" value="yt" name="ltype" id="yt"  required="true"> Youtube*
						<span class="form-check-sign">
						  <span class="check"></span>
						</span>
					  </label>
					</div>
					<div id="type_error"></div>
				</div>
			</div>
			<div class="form-group mb-4" id="sh_file" style="display:none">
				<code>PDF/Word/Excel</code>
				<div class="custom-file">
					<input type="file" class="custom-file-input" name="lfiles" id="lfiles" accept="*.pdf, *.doc, *.docs, *.xls, *.xlsx" required="true">
					<label class="custom-file-label" for="lfiles">Choose program brochure</label>
				</div>
			</div>
			<div class="form group" id="sh_lkyt" style="display:none">
				<textarea class="form-control w-100" cols="80" rows="5" name="llink" id="llink" placeholder="Copy-Paste your link or youtube embedded link here"  required="true"></textarea>
			</div>
			<div class="form-group mt-3">
				<label for="ldate" class="text-dark">Lecture Date-Time</label>
				<input type="datetime-local" class="form-control" name="ldate" id="ldate">
			</div>
			<div class="form-group">
				<div class="togglebutton">
					<label class="text-dark">
					  <input type="checkbox" name="lnotify" id="lnotify" value="1">
					  <span class="toggle"></span>
					  Notify?
					</label>
				  </div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-link pull-right" id="clSubmit"></button>
				<input type="reset" style="visibility:hidden;"/>
				<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</form>
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