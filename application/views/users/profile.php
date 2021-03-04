<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/croppie/croppie.css" />
<style>
#upload-demo{
	width: 400px;
	height: 400px;
	padding-bottom:25px;
}
</style>

<div class="content">
	<div class="container-fluid">
		<div class="row">
			<?php if($this->session->flashdata('success')){ ?>
				<script>
					$.notify({
						icon:"add_alert",
						message: <?php echo $this->session->flashdata('success'); ?>},
						{type:'success',
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				</script>
			<?php } if($this->session->flashdata('error')){ ?>
				<script>
					$.notify({
						icon:"add_alert",
						message: <?php echo $this->session->flashdata('error'); ?>},
						{type:'danger',
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				</script>
			<?php } ?>
			<div class="col-md-4">
				<div class="card card-profile">
					<div class="card-avatar">
					  <a href="javascript:;">
						<img class="img" src="<?php echo base_url().$udetails[0]->photo_sm; ?>" onerror="this.src='<?php echo base_url(); ?>assets/img/default-avatar.png'" />
					  </a>
					</div>
					<div class="card-body">
					  <h4 class="card-title"><?php echo trim($udetails[0]->first_name." ".$udetails[0]->last_name); ?></h4>
					  <p class="card-description">
						<?php
							echo $udetails[0]->email.'<br>'.ucfirst($_SESSION['userData']['utype']);
						?>
					  </p>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="wizard-container">
				  <div class="card card-wizard" data-color="purple" id="wizardProfile">
					<form id="frmProfile" action="<?php echo base_url('Student/updateProfile'); ?>" method="POST" enctype="multipart/form-data">
					  <div class="card-header text-center">
						<h3 class="card-title">
						  Build Your Profile
						</h3>
						<h5 class="card-description">This information will let us know more about you.</h5>
					  </div>
					  <div class="wizard-navigation">
						<ul class="nav nav-pills">
						  <li class="nav-item">
							<a class="nav-link active" href="#personal" id="can_personal" data-toggle="tab" role="tab">
							  Personal
							</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#contact" id="can_contact" data-toggle="tab" role="tab">
							  Contact
							</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#academic" id="can_academic" data-toggle="tab" role="tab">
							  Academic and Skills
							</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#social" id="can_social" data-toggle="tab" role="tab">
							  Social
							</a>
						  </li>
						</ul>
					  </div>
					  <div class="card-body">
						<div class="tab-content">
						  <div class="tab-pane active" id="personal">
							<div class="row justify-content-center">
							  <div class="col-sm-4">
								<div class="picture-container">
								  <div class="picture">
									<img src="<?php echo base_url().$udetails[0]->photo_sm; ?>" onerror="this.src='<?php echo base_url(); ?>assets/img/default-avatar.png'" class="picture-src" id="wizardPicturePreview" title="" />
									<input type="file" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png">
									<input type="hidden" name="crop_img" id="crop_img" value="" />
								  </div>
								  <h6 class="description">Choose Picture</h6>
								</div>
							  </div>
							  <div class="col-sm-6">
								<div class="input-group form-control-lg">
								  <div class="input-group-prepend">
									<span class="input-group-text">
									  <i class="material-icons">face</i>
									</span>
								  </div>
								  <div class="form-group">
									<label for="firstname" class="">First Name (required)</label>
									<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $udetails[0]->first_name; ?>">
								  </div>
								</div>
								<div class="input-group form-control-lg">
								  <div class="input-group-prepend">
									<span class="input-group-text">
									  <i class="material-icons">record_voice_over</i>
									</span>
								  </div>
								  <div class="form-group">
									<label for="lastname" class="">Last Name</label>
									<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $udetails[0]->last_name; ?>">
								  </div>
								</div>
							  </div>
							</div>
							<div class="row justify-content-center">
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="material-icons">today</i>
											</span>
										  </div>
										<div class="form-group">
											<label for="dob" class=""> Date of Birth *</label>
											<input type="date" class="form-control" name="dob" id="dob" value="<?php echo ($udetails[0]->dateofbirth!="")? $udetails[0]->dateofbirth:'' ; ?>">
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="material-icons">wc</i>
											</span>
										  </div>
										<div class="form-group">
											<select class="selectpicker" data-style="select-with-transition" name="gender" id="gender" title="Choose Gender*">
												<option value="M" <?php if(trim($udetails[0]->gender)=='M'){ ?>selected<?php } ?>>Male </option>
												<option value="F" <?php if(trim($udetails[0]->gender)=='F'){ ?>selected<?php } ?>>Female</option>
												<option value="O" <?php if(trim($udetails[0]->gender)=='O'){ ?>selected<?php } ?>>Others</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-sm-6">
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="resume" id="resume" accept=".doc, .docx, application/pdf">
										<label class="custom-file-label text-dark" for="resume">Choose Resume</label>
									  </div>
								</div>
							</div>
						  </div>
						  <div class="tab-pane" id="contact">
							<div class="row justify-content-center">
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
									  <div class="input-group-prepend">
										<span class="input-group-text">
										  <i class="material-icons">email</i>
										</span>
									  </div>
									  <div class="form-group">
										<label for="email" class="">Email (required)</label>
										<input type="email" class="form-control" id="email" name="email" value="<?php echo $udetails[0]->email; ?>" readonly>
									  </div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="material-icons">phonelink_ring</i>
											</span>
										  </div>
										<div class="form-group">
											<label for="phoneNumber" class="">Phone *</label>
											<input class="form-control" type="text" name="phone" id="phone" value="<?php echo $udetails[0]->phone; ?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
									  <div class="input-group-prepend">
										<span class="input-group-text">
										  <i class="material-icons">alternate_email</i>
										</span>
									  </div>
									  <div class="form-group">
										<label for="email" class="">Alternate Email</label>
										<input type="email" class="form-control" id="alt_email" name="alt_email" value="<?php echo $udetails[0]->alt_email; ?>">
									  </div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="material-icons">smartphone</i>
											</span>
										  </div>
										<div class="form-group">
											<label for="phoneNumber" class="">Alternate Phone</label>
											<input class="form-control" type="text" name="alt_phone" id="alt_phone" value="<?php echo $udetails[0]->alt_phone; ?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-sm-12">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="material-icons">home</i>
											</span>
										  </div>
										<div class="form-group">
											<textarea class="form-control" name="address" id="address" placeholder="Address"><?php echo $udetails[0]->address; ?></textarea>
										</div>
									</div>
								</div>
							</div>
						  </div>
						  
						  <div class="tab-pane" id="academic">
							<div class="row justify-content-center">
								<div class="col-sm-6">
									<div class="form-group">
										<select class="selectpicker" name="skills[]" id="skills" multiple="multiple" data-style="select-with-transition" data-size="4" data-title="Select multiple skills">
											<?php
											$usk = array();
											foreach($uskills as $urow){
												array_push($usk, $urow->id);
											}
											foreach($skills as $skow){ 
												if(in_array($skow->id, $usk)){
													echo '<option value="'.$skow->id.'" selected>'.$skow->name.'</option>';
												}else{
													echo '<option value="'.$skow->id.'">'.$skow->name.'</option>';
												}
												
											} ?>
										</select>
									</div>
								</div>
							</div>
							<button type="button" onClick="addMore();" class="btn btn-info btn-sm"><i class="material-icons">add</i> Add Academic</button>
							<input type="hidden" name="educount" id="educount" value="<?php echo count($uacademic)-1; ?>"/>
							<?php if(!empty($uacademic)){ $i=0; foreach($uacademic as $uaow){ ?>
							<div class="border-top p-2" id="fldset_<?php echo $i; ?>">
								<button type="button" onClick="removeNow(<?php echo $i; ?>)" class="close">x</button>
								<div class="clearfix"></div>
								<div class="row justify-content-center">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="board_<?php echo $i; ?>" class="bmd-label-floating">Board/University <span class="text-danger">*</span></label>
											<input type="text" name="board_<?php echo $i; ?>" id="board_<?php echo $i; ?>" class="form-control" value="<?php echo trim($uaow->board); ?>" required>
										  </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<select class="selectpicker" name="degree_<?php echo $i; ?>" id="degree_<?php echo $i; ?>" data-style="select-with-transition" title="Select degree" data-size="5">
												<?php 
													if(!empty($degree)){
														foreach($degree as $drow){
															if($drow->id==$uaow->degree_id){
																echo '<option value="'.$drow->id.'" selected>'.$drow->degree_name.' ('.$drow->short.')</option>';
															}else{
																echo '<option value="'.$drow->id.'">'.$drow->degree_name.' ('.$drow->short.')</option>';
															}
														}
													}
												?>
											</select>
										  </div>
									</div>
								</div>
								<div class="row justify-content-center">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="org_<?php echo $i; ?>" class="bmd-label-floating">College/School Name<span class="text-danger">*</span></label>
											<input type="text" name="org_<?php echo $i; ?>" id="org_<?php echo $i; ?>" class="form-control" value="<?php echo trim($uaow->organization); ?>" required>
										  </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<div class="togglebutton">
												<label>
												  Present
												  <input type="checkbox" name="status_<?php echo $i; ?>" id="status_<?php echo $i; ?>" onChange="toggleInfo(<?php echo $i; ?>)" value="1" <?php if(trim($uaow->aca_status=='Completed')){ echo 'checked'; } ?>>
												  <span class="toggle"></span>
												  Completed
												</label>
											  </div>
										</div>
										<script>
											toggleInfo(<?php echo $i; ?>);
										</script>
									</div>
								</div>
								<div class="row" style="display:none" id="moreInfo_<?php echo $i; ?>">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="passout_<?php echo $i; ?>" class="bmd-label-floating">Passout <span class="text-danger">*</span></label>
											<input type="text" name="passout_<?php echo $i; ?>" id="passout_<?php echo $i; ?>" digits="true" maxlength="4" minlength="4" class="form-control" value="<?php echo trim($uaow->passout_year); ?>" required>
										  </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="marks_<?php echo $i; ?>" class="bmd-label-floating">CGPA/Percentage <span class="text-danger">*</span></label>
											<input type="number" name="marks_<?php echo $i; ?>" id="marks_<?php echo $i; ?>" class="form-control" value="<?php echo trim($uaow->marks_per); ?>" required>
										  </div>
									</div>
								</div>
							</div>
							<?php $i++; } } ?>
							<span id="edu_more"></span>
						  </div>
						  
						  <div class="tab-pane" id="social">
							<div class="row justify-content-center">
								<div class="col-12">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="fa fa-facebook"></i>
											</span>
										  </div>
										<div class="form-group">
											<input type="url" name="facebook" id="facebook" class="form-control" value="<?php echo $udetails[0]->facebook_link; ?>" placeholder="Facebook profile link">
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="fa fa-google"></i>
											</span>
										  </div>
										<div class="form-group">
											<input type="url" name="google" id="google" class="form-control" value="<?php echo $udetails[0]->google_link; ?>" placeholder="Google PLus profile link">
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="fa fa-linkedin"></i>
											</span>
										  </div>
										<div class="form-group">
											<input type="url" name="linkedin" id="linkedin" class="form-control" value="<?php echo $udetails[0]->linkedin_link; ?>" placeholder="Linkedin profile link">
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text">
											  <i class="fa fa-link"></i>
											</span>
										  </div>
										<div class="form-group">
											<input type="url" name="website" id="website" class="form-control" value="<?php echo $udetails[0]->website; ?>" placeholder="Own website link">
										</div>
									</div>
								</div>
							</div>
						  </div>
						</div>
					  </div>
					  <div class="card-footer">
						<div class="mr-auto">
						  <input type="button" class="btn btn-previous btn-fill btn-default btn-wd disabled" name="previous" value="Previous">
						</div>
						<div class="ml-auto">
						  <input type="button" class="btn btn-next btn-fill btn-primary btn-wd" name="next" value="Next">
						  <input type="submit" class="btn btn-finish btn-fill btn-primary btn-wd" name="finish" value="Finish" style="display: none;">
						</div>
						<div class="clearfix"></div>
					  </div>
					</form>
				  </div>
				</div>
			
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
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
<script type="text/javascript" src="<?php echo base_url('assets/js/pvalidation.js'); ?>"></script>
<script src="<?php echo base_url().'assets/vendor/croppie/croppie.js'; ?>" type="text/javascript"></script>
<script>
$(document).ready(function(){
	demo.initMaterialWizard();
	setTimeout(function() {
	$('.card.card-wizard').addClass('active');
	}, 600);
	$('#resume').on('change', function () {
		var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});
});
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
			width: 250,
			height: 250,
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

	$('#avatar').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();
	$('#cancelCropBtn').data('id', imageId); readFile(this); });
	$('#cropImageBtn').on('click', function (ev) {
		$uploadCrop.croppie('result', {
			type: 'base64',
			format: 'jpeg',
			size: {width: 250, height: 250}
		}).then(function (resp) {crop_img
			$('#wizardPicturePreview').attr('src', resp);
			$('#crop_img').attr('value', resp);
			$('#cropImagePop').modal('hide');
		});
	});
	function addMore()
	{
		var count = parseInt($('#educount').val());
		count++;
		var fieldset = '<div class="border-top p-2" id="fldset_'+count+'"><button type="button" onClick="removeNow('+count+')" class="close">&times</button><div class="clearfix"></div><div class="row justify-content-center"><div class="col-sm-6"><div class="form-group"><label for="board_'+count+'" class="bmd-label-floating">Board/University <span class="text-danger">*</span></label><input type="text" name="board_'+count+'" id="board_'+count+'" class="form-control" required></div></div><div class="col-sm-6"><div class="form-group"><select class="selectpicker" name="degree_'+count+'" id="degree_'+count+'" data-style="select-with-transition" title="Select degree" data-size="5">';
		<?php if(!empty($degree)){ foreach($degree as $drow){ ?>
		fieldset+= '<option value="<?php echo $drow->id; ?>"><?php echo $drow->degree_name.' ('.$drow->short.')'; ?></option>';
		<?php } } ?>
		fieldset+= '</select></div></div></div><div class="row justify-content-center"><div class="col-sm-6"><div class="form-group"><label for="org_1" class="bmd-label-floating">College/School Name<span class="text-danger">*</span></label><input type="text" name="org_'+count+'" id="org_'+count+'" class="form-control" required></div></div><div class="col-sm-6"><div class="form-group"><div class="togglebutton"><label>Present <input type="checkbox" name="status_'+count+'" id="status_'+count+'" onChange="toggleInfo('+count+')" value="1"><span class="toggle"></span>Completed</label></div></div></div></div><div class="row" style="display:none" id="moreInfo_'+count+'"><div class="col-sm-6"><div class="form-group"><label for="passout_1" class="bmd-label-floating">Passout <span class="text-danger">*</span></label><input type="text" name="passout_'+count+'" id="passout_'+count+'" digits="true" maxlength="4" minlength="4" class="form-control" required></div></div><div class="col-sm-6"><div class="form-group"><label for="marks_1" class="bmd-label-floating">CGPA/Percentage <span class="text-danger">*</span></label><input type="number" name="marks_'+count+'" id="marks_'+count+'" class="form-control" required></div></div></div></div>';
		
		$('#educount').val(count);
		$('#edu_more').append(fieldset);
		$('#degree_'+count).selectpicker('refresh');
	}
	function removeNow(id)
	{
		$('#fldset_'+id).remove();
	}
	function toggleInfo(numval)
	{
		if($('#status_'+numval).is(':checked')){
			$('#moreInfo_'+numval).show();
		}else{
			$('#moreInfo_'+numval).hide();
		}
	}
</script>