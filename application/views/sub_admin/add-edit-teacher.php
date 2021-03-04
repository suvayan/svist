<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/croppie/croppie.css" />
<div class="content">
    <div class="container">
		<div class="row">
            <div class="col-lg-10 col-md-10 mx-auto">
                <div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title"><?php echo $oper.' Teacher'; ?>
							<a href="<?php echo base_url('Subadmin/teacherMaster'); ?>" class="btn btn-primary btn-sm pull-right"><i class="material-icons">list</i> Teacher List</a>
						</h3>
					</div>
					<div class="card-body">
						<form method="POST" action="<?php echo base_url('Subadmin/cuTeacher'); ?>" id="frmTeacher" enctype="multipart/form-data">
							<?php if($prof[0]->id==0):?>
							<div class="row">
                                <div class="col-sm-12 col-md-12">
									<div class="form-group"> 
										<select class="selectpicker" multiple data-style="select-with-transition" data-title="Select department <span class='text-danger'>*</span>" id="dept" name="dept[]">
                                            <?php
                                                if(!empty($department)){
                                                    foreach($department as $dept){
                                                        echo '<option value="'.$dept->id.'">'.$dept->title.'</option>';
                                                    }
                                                }
                                            ?>
										</select>
									</div>     
								</div>
                            </div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="passwd">Password <span class="text-danger">*</span></label>
										<input type="password" name="passwd" id="passwd" class="form-control" autocomplete="off"/>
									  </div>
									  
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="repasswd">Retype Password <span class="text-danger">*</span></label>
										<input type="password" name="repasswd" id="repasswd" class="form-control" autocomplete="off"/>
									  </div>
								</div>
							</div>
							<?php endif;?>
							<div class="row">
								<div class="col-md-6">
									<div class="picture-container">
										<div class="picture">
											<label>
												<img src="<?php echo base_url('assets/img/users/'.$prof[0]->photo_sm); ?>" class="picture-src" onerror="this.src='<?php echo base_url('assets/img/default-avatar.png'); ?>'" id="wizardPicturePreview" title="" width="250"/>
												<input type="file" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png" style="display:none;" onchange="avaterOnChange()">
											</label>
											<input type="hidden" name="crop_img" id="crop_img" value="" />
										</div>
									</div>
									
								</div>
								<div class="col-md-6" id="cropImagePop" style="display:none;">
									<div id="upload-demo" class="center-block"></div>
									<br>
									<button type="button" onClick="cropImageBtn()" class="btn btn-primary">Crop</button>
									<button type="button" class="btn btn-danger btn-link" onClick="$(`#cropImagePop`).hide()">Cancel</button>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<div class="form-group">
										<select class="selectpicker w-100" data-style="select-with-transition" data-title="Sal. <span class='text-danger'>*</span>" name="salutation" id="salutation">
											<option value="Mr" <?php if(trim($prof[0]->salutation)=='Mr'){ echo 'selected'; } ?>>Mr</option>
											<option value="Mrs" <?php if(trim($prof[0]->salutation)=='Mrs'){ echo 'selected'; } ?>>Mrs</option>
											<option value="Ms" <?php if(trim($prof[0]->salutation)=='Ms'){ echo 'selected'; } ?>>Ms</option>
											<option value="Prof" <?php if(trim($prof[0]->salutation)=='Prof'){ echo 'selected'; } ?>>Prof</option>
											<option value="Dr" <?php if(trim($prof[0]->salutation)=='Dr'){ echo 'selected'; } ?>>Dr</option>
										</select>
									  </div>
								</div>
								<div class="col-sm-5">
									<div class="form-group">
										<label for="fname">Firstname <span class="text-danger">*</span></label>
										<input type="text" name="fname" id="fname" class="form-control" value="<?php echo $prof[0]->first_name; ?>"/>
										<input type="hidden" name="user_type" id="user_type" value="teacher"/>
										<input type="hidden" name="uid" id="uid" value="<?php echo $prof[0]->id; ?>"/>
										<input type="hidden" name="photo_sm" id="photo_sm" value="<?php echo $prof[0]->photo_sm; ?>"/>
									  </div>
								</div>
								<div class="col-sm-5">
									<div class="form-group">
										<label for="lname">Lastname <span class="text-danger">*</span></label>
										<input type="text" name="lname" id="lname" class="form-control" value="<?php echo $prof[0]->last_name; ?>"/>
									  </div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="email">Email <span class="text-danger">*</span></label>
										<input type="text" name="email" id="email" class="form-control" value="<?php echo $prof[0]->email; ?>"/>
									  </div>	
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="phone">Phone <span class="text-danger">*</span></label>
										<input type="text" name="phone" id="phone" class="form-control" value="<?php echo $prof[0]->phone; ?>"/>
									  </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="linkedin">Linkedin</label>
										<input type="text" class="form-control" name="linkedin" id="linkedin" value="<?php echo $prof[0]->linkedin_link; ?>">
									</div>
									<div class="form-group">
										<label for="about_me">About Me</label><br>
										<textarea class="form-control" name="about_me" id="about_me" rows="3"><?php echo $prof[0]->about_me; ?></textarea>
										<script>CKEDITOR.replace("about_me");</script>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-info btn-md pull-right"><?php echo $oper; ?></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>/assets/js/sub-admin.js"></script>
<script src="<?php echo base_url().'assets/vendor/croppie/croppie.js'; ?>" type="text/javascript"></script>
<script>
	var $uploadCrop;
	var tempFilename;
	var rawImg;
	var imageId;
	
	function readFile(input) {
		if (input.files && input.files[0]) {
		  var reader = new FileReader();
			reader.onload = function (e) {
				$('#upload-demo').addClass('ready');
				//$('#cropImagePop').modal('show');
				rawImg = e.target.result;
				$('#cropImagePop').show(()=>{
					uploadBind();
				});
			}
			reader.readAsDataURL(input.files[0]);
		}
		else {
			Swal.fire("Sorry","You're browser doesn't support the FileReader API", "error");
		}
	}
	
	function uploadBind()
	{
		$uploadCrop = $('#upload-demo').croppie({
			viewport: {
				width: 250,
				height: 250,
			},
			enforceBoundary: false,
			enableExif: true
		});
		//alert('Shown pop');
		$uploadCrop.croppie('bind', {
			url: rawImg
		}).then(function(){
			console.log('jQuery bind complete');
		});
	}
	
	function cropImageBtn()
	{
		$uploadCrop.croppie('result', {
			type: 'base64',
			format: 'jpeg',
			size: {width: 250, height: 250}
		}).then(function (resp) {crop_img
			$('#wizardPicturePreview').attr('src', resp);
			$('#crop_img').attr('value', resp);
			$('#cropImagePop').hide();
		});
	}

	function avaterOnChange(){
		imageId = document.getElementById('avatar'); 
		readFile(imageId);
	}
</script>