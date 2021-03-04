<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/croppie/croppie.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/select2/dist/css/select2.min.css" />
<style>
.loader {
  background-color: #ffffff;
  opacity:0.5;
  position: fixed;
  z-index: 999999;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
}

.loader img {
  position: absolute;
  top: 50%;
  left: 50%;
  text-align: center;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
#upload-demo{
	width: 300px;
	height: 300px;
	padding-bottom:25px;
}
</style>
<div class="loader" id="loading" style="display:none;">
	<img src="<?php echo base_url().'assets/img/loading.gif'; ?>">
</div>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-md-8">
				<div class="card ">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title"><?php echo $prog[0]->title.' ('.$prog[0]->code.')'; ?></h4>
					  </div>
					  <a href="<?php echo base_url().'Teacher/viewProgram/?id=',base64_encode($prog[0]->id); ?>" class="btn btn-sm btn-primary pull-right"><i class="material-icons">list</i> Back to Program Details</a>
					</div>
					<div class="card-body">
						<h6 style="font-variant:normal">
									
							<?php
								if($prog[0]->facebook!=""){
									echo '<a href="'.$prog[0]->facebook.'" target="_blank" class="btn btn-just-icon btn-link btn-facebook"><i class="fa fa-facebook"> </i></a>';
								}
								if($prog[0]->twitter!=""){
									echo '<a href="'.$prog[0]->twitter.'" target="_blank" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"> </i></a>';
								}
								if($prog[0]->linkedin!=""){
									echo '<a href="'.$prog[0]->linkedin.'" target="_blank" class="btn btn-just-icon btn-link btn-linkedin"><i class="fa fa-linkedin"> </i></a>';
								}
								$type = trim($prog[0]->ptype);
								$category = trim($prog[0]->category);
								if($type!=""){
											echo $type.' Program';
										}
										if($category!=""){
											echo ', Category : '.$category.', ';
										}
										$dur = intval(trim($prog[0]->duration));
										echo 'Duration: '.$dur.' '.trim($prog[0]->dtype).'(s), ';
								if(trim($prog[0]->feetype)=='paid'){
									echo 'Total Fees: '.trim($prog[0]->total_fee).',   ';
								}
								if(trim($prog[0]->total_credit)!=""){
									echo 'Total Credit: '.$prog[0]->total_credit;
								}
							?>
						</h6>
					</div>
				</div>
				
				<div class="card">
					<div class="card-body">
						<div id="accordion" role="tablist">
							<div class="card-collapse">
							  <div class="card-header bg-info" role="tab" id="headingOne">
								<h5 class="mb-0">
								  <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed text-white pl-2">
									Institutes
									<i class="material-icons">keyboard_arrow_down</i>
								  </a>
								</h5>
							  </div>
							  <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
								<div class="card-body">
								  <button class="btn btn-sm btn-primary pull-right" onClick="modalInstitute('add', <?php echo $prog_id; ?>, '')">Add Institute</button>
								  <div class="material-datatables">
									<table class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th width="5%">Sl.</th>
												<th width="90%">Details</th>
												<th width="5%">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php $i=1; foreach($institute as $irow){ ?>
												<tr>
													<td><?php echo $i; ?></td>
													<td style="word-break:break-all;">
														<?php 
															if($irow->logo!=""){
																if(file_exists('./assets/img/institute/'.$irow->logo)){
																	echo '<img src="'.base_url('assets/img/institute/'.$irow->logo).'" class"img-thumbnail" style="width: 60px; float:left;margin-right: 5px;"';
																}
															}
															echo '<p>'.$irow->title; 
															if($irow->website!=""){
																echo '<br>'.$irow->website;
															}
															if($irow->contact_info!=""){
																echo '<br>'.$irow->contact_info;
															}
															echo '</p>';
														?>
													</td>
													<td class="td-actions text-right">
														<button type="button" title="Edit" rel="tooltip" class="btn btn-success" onClick="modalInstitute('edit', <?php echo $prog_id; ?>, <?php echo $irow->id; ?>)">
														  <i class="material-icons">edit</i>
														</button>
														<button type="button" title="Delete" rel="tooltip" class="btn btn-warning" onClick="deleteInstitute(<?php echo $irow->id; ?>)">
														  <i class="material-icons">delete</i>
														</button>
													  </td>
												</tr>
											<?php $i++; } ?>
										</tbody>
									</table>
								</div>
								</div>
							  </div>
							</div>
							<div class="card-collapse">
							  <div class="card-header bg-info" role="tab" id="headingTwo">
								<h5 class="mb-0">
								  <a class="collapsed text-white pl-2" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Streams
									<i class="material-icons">keyboard_arrow_down</i>
								  </a>
								</h5>
							  </div>
							  <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body">
								  <button class="btn btn-sm btn-primary pull-right" onClick="modalStream('add', <?php echo $prog_id; ?>, '')">Add Stream</button>
								  <div class="material-datatables">
									<table class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th width="5%">Sl.</th>
												<th width="90%">Details</th>
												<th width="5%">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php $i=1; foreach($stream as $srow){ ?>
												<tr>
													<td><?php echo $i; ?></td>
													<td style="word-break:break-all;">
														<?php 
															echo $srow->title; 
															if($srow->website!=""){
																echo '<br>'.$srow->website;
															}
															if($srow->institute!=""){
																echo '<br>'.$srow->institute;
															}
															if($srow->contact_info!=""){
																echo '<br>'.$srow->contact_info;
															}
														?>
													</td>
													<td class="td-actions text-right">
														<button type="button" title="Edit" rel="tooltip" class="btn btn-success" onClick="modalStream('edit', <?php echo $prog_id; ?>, <?php echo $srow->id; ?>)">
														  <i class="material-icons">edit</i>
														</button>
														<button type="button" title="Delete" rel="tooltip" class="btn btn-warning" onClick="deleteStream(<?php echo $srow->id; ?>)">
														  <i class="material-icons">delete</i>
														</button>
													  </td>
												</tr>
											<?php $i++; } ?>
										</tbody>
									</table>
								</div>
								</div>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title">Notices
						</h4>
					</div>
					<div class="card-body">
						<div class="notice_ticker" style="height:30vh;">
							<ul class="list-group" id="notc_details">
							
							</ul>
						</div>
					</div>
					<div class="card-footer">
						<button class="btn btn-info btn-sm pull-right">Know More</button>
					</div>
				</div>
				<a href="<?php echo base_url().'Teacher/progTeachers/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Teachers</a>
				<a href="<?php echo base_url().'Teacher/progStudents/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Students</a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:9999 !important;">
	<div class="modal-dialog">
	  <div class="modal-content" style="width: fit-content;">
		<div class="modal-header">
		  <h4 class="modal-title">Edit Photo<br><small class="text-danger">Please do crop else the image won't be saved.</small></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
		  <div id="upload-demo" class="center-block"></div>
		</div>
		<div class="modal-footer">
		  <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
		  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
		</div>
	  </div>
	</div>
</div>
<div class="modal fade" id="addInstitute" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title" id="inst_title"></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<form id="frmInstitute" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="text-center">
					<div class="fileinput fileinput-new text-center">
						<div class="fileinput-new thumbnail">
							<img src="<?php echo base_url(); ?>assets/img/image_placeholder.jpg" class="picture-src" id="wizardPicturePreview" onerror="this.src='<?php echo base_url(); ?>assets/img/image_placeholder.jpg'" style="width:200px;" />
						</div>
					</div>
					<div class="custom-file">
						<input type="hidden" name="crop_img" id="crop_img" value="" />
						<input type="file" class="custom-file-input" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png">
						<label class="custom-file-label text-dark" for="brochure">Upload Institute Logo</label>
					</div>
				</div><br>
				<div class="form-group">
					<input type="hidden" name="iprog_id" id="iprog_id" value="">
					<input type="hidden" name="org_id" id="org_id" value="">
					<label for="org_title">Institute Title*</label>
					<input type="text" name="org_title" id="org_title" class="form-control">
				</div>
				<div class="form-group">
					<label for="org_web">Website</label>
					<input type="url" name="org_web" id="org_web" class="form-control">
				</div>
				<div class="form-group">
					<label for="org_contact" class="">Contact</label><br>
					<textarea class="form-control" name="org_contact" id="org_contact" cols="80" rows="5" style="width:100%;"></textarea>
				</div>
			</div>
			<div class="modal-footer">
			  <input type="reset" style="visibility: hidden;"/>
			  <button type="button" id="orgSubmit" class="btn btn-link"></button>
			  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</form>
	  </div>
	</div>
</div>
<div class="modal fade" id="addStream" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title" id="s_title"></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<form id="frmStream">
			<div class="modal-body">
				<div class="form-group">
					<input type="hidden" name="prog_id" id="prog_id" value="">
					<input type="hidden" name="strm_id" id="strm_id" value="">
					<label for="strm_title">Stream Title*</label>
					<input type="text" name="strm_title" id="strm_title" class="form-control">
				</div>
				<div class="form-group">
					<select name="strm_org" id="strm_org" class="selectpicker" data-style="select-with-transition" title="Select an institute*">
						<option value="" disabled>Select one</option>
						<?php 
							foreach($institute as $row){ 
								echo '<option value="'.$row->id.'">'.$row->title.'</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="strm_web">Website</label>
					<input type="url" name="strm_web" id="strm_web" class="form-control">
				</div>
				<div class="form-group">
					<label for="strm_contact" class="">Contact</label><br>
					<textarea class="form-control" name="strm_contact" id="strm_contact" cols="80" rows="5" style="width:100%;"></textarea>
				</div>
			</div>
			<div class="modal-footer">
			  <input type="reset" style="visibility: hidden;"/>
			  <button type="button" id="strmSubmit" class="btn btn-link"></button>
			  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</form>
	  </div>
	</div>
</div>
<script src="<?php echo base_url().'assets/js/institutestream.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/vendor/croppie/croppie.js'; ?>" type="text/javascript"></script>
<script>
var $uploadCrop,
	tempFilename,
	rawImg,
	imageId;
	
	function readFile(input) {
		if (input.files && input.files[0]) {
		  var reader = new FileReader();
			reader.onload = function (e) {
				$('.upload-demo').addClass('ready');
				$('#cropImagePop').modal('show');
				rawImg = e.target.result;
			}
			reader.readAsDataURL(input.files[0]);
		}
		else {
			Swal.fire("Sorry","You're browser doesn't support the FileReader API", "error");
		}
	}
	$uploadCrop = $('#upload-demo').croppie({
		viewport: {
			width: 200,
			height: 200,
		},
		enforceBoundary: false,
		enableExif: true
	});
	$('#cropImagePop').on('shown.bs.modal', function(){
		// alert('Shown pop');
		$uploadCrop.croppie('bind', {
			url: rawImg
		}).then(function(){
			console.log('jQuery bind complete');
		});
	});
	
	$("#avatar").on("change", function() {
	  var fileName = $(this).val().split("\\").pop();
	  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});

	$('#avatar').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();
	$('#cancelCropBtn').data('id', imageId); readFile(this); });
	$('#cropImageBtn').on('click', function (ev) {
		$uploadCrop.croppie('result', {
			type: 'base64',
			format: 'jpeg',
			size: {width: 200, height: 200}
		}).then(function (resp) {crop_img
			$('#wizardPicturePreview').attr('src', resp);
			$('#crop_img').attr('value', resp);
			$('#cropImagePop').modal('hide');
		});
	});
</script>