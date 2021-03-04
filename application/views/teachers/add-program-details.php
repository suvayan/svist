<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/croppie/croppie.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/select2/dist/css/select2.min.css" />
<style>
.form-error{
	color:red;
}
#upload-demo{
	width: 500px;
	height: 400px;
	padding-bottom:25px;
}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 mx-auto">
				<div class="card">
					<div class="card-header card-header-primary card-header-icon">
					  <div class="card-icon">
						<i class="material-icons">create</i>
					  </div>
					  <h4 class="card-title">Update Program Details <small class="text-danger">*'s are important</small></h4>
					</div>
					<div class="card-body">
						<form action="<?php echo base_url('Teacher/cuProgramDetails'); ?>" enctype="multipart/form-data" id="frmProgram" method="POST" style="width: 100%;">
							
							<div class="row justify-content-center mb-4">
								<div class="col-sm-6 mx-auto">
									<div class="fileinput fileinput-new text-center">
										<div class="fileinput-new thumbnail">
											<img src="<?php echo base_url().'assets/img/banner/'.$prog[0]->banner; ?>" onerror="this.src='<?php echo base_url(); ?>assets/img/image_placeholder.jpg'" class="picture-src" id="wizardPicturePreview" title="" />
										</div>
									</div>
									<div class="custom-file">
										<input type="hidden" name="crop_img" id="crop_img" value="" />
										<input type="file" class="custom-file-input" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png">
										<label class="custom-file-label text-dark" for="brochure">Upload Banner Image</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
									  <label for="title">Program Title <span class="text-danger">*</span></label>
									  <input class="form-control" id="title" name="title" value="<?php echo trim($prog[0]->title); ?>" readonly>
									  <input type="hidden" name="pid" id="pid" value="<?php echo $prog[0]->id; ?>"/>
									</div>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-sm-6">
									<?php if($prog[0]->id!=''){ 
										if($prog[0]->program_brochure!=""){
											if(file_exists('./uploads/programs/'.$prog[0]->program_brochure)){
												echo '<h6 class="text-center"><a href="'.base_url('uploads/programs/'.$prog[0]->program_brochure).'" target="_blank"></a></h6>';
											}else{
												echo '<h6 class="text-center">File is missing.</h6>';
											}
										}else{
											echo '<h6 class="text-center">Upload the brochure below</h6>';
										}
									} ?>
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="schedule" id="schedule" accept=".doc, .docx, application/pdf,application/vnd.ms-excel">
										<label class="custom-file-label text-dark" for="schedule">Choose program brochure</label>
									  </div>
								</div>
								<div class="col-sm-6">
									<?php if($prog[0]->id!=''){ 
										if($prog[0]->certificate_sample!=""){
											if(file_exists('./uploads/programs/'.$prog[0]->certificate_sample)){
												echo '<h6 class="text-center"><a href="'.base_url('uploads/programs/'.$prog[0]->certificate_sample).'" target="_blank"></a></h6>';
											}else{
												echo '<h6 class="text-center">File is missing.</h6>';
											}
										}else{
											echo '<h6 class="text-center">Upload the certificate below</h6>';
										}
									} ?>
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="brochure" id="brochure" accept="image/jpg, image/jpeg, image/png">
										<label class="custom-file-label text-dark" for="brochure">Choose certificate sample</label>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="intro">Introduction (YouTube Share link)</label><br>
										<textarea name="intro" id="intro" class="form-control" cols="80" rows="5"><?php echo $prog[0]->intro_video_link; ?></textarea>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="fdetails">Overview</label><br>
										<textarea name="overview" id="overview" class="form-control" cols="80" rows="5"><?php echo $prog[0]->overview; ?></textarea>
									</div>
								</div>
							</div>
							<?php if(trim($prog[0]->feetype)=='Paid'){ ?>
							<div class="row mb-3" id="feesd">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="fdetails">Fees Details</label><br>
										<textarea name="fdetails" id="fdetails" class="form-control" cols="80" rows="5"><?php echo $prog[0]->fee_details; ?></textarea>
									</div>
								</div>
							</div>
							<?php } ?>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="fdetails">Reason, Why learn?</label><br>
										<textarea name="wlearn" id="wlearn" class="form-control" cols="80" rows="5"><?php echo $prog[0]->why_learn; ?></textarea>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="fdetails">Requirements for admission</label><br>
										<textarea name="requirement" id="requirement" class="form-control" cols="80" rows="5"><?php echo $prog[0]->requirements; ?></textarea>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="contact_info">Contact Information</label><br>
										<textarea name="contact_info" id="contact_info" class="form-control" cols="80" rows="5"><?php echo $prog[0]->contact_info; ?></textarea>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="placement">Placement</label><br>
										<textarea name="placement" id="placement" class="form-control" cols="80" rows="5"><?php echo $adm[0]->placement; ?></textarea>
									</div>
								</div>
							</div>
							<div class="justify-content-center text-center">
								<button type="submit" class="btn btn-primary btn-md pull-right">Update</button>
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
<script src="<?php echo base_url().'assets/js/program.js'; ?>"></script>
<script src="<?php echo base_url().'assets/vendor/croppie/croppie.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/ckeditor.js"></script>
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
			width: 400,
			height: 266,
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
	
	$('#avatar').on('change', function () {
		var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		imageId = $(this).data('id'); tempFilename = $(this).val();
		$('#cancelCropBtn').data('id', imageId); readFile(this); 
	});
	$('#cropImageBtn').on('click', function (ev) {
		$uploadCrop.croppie('result', {
			type: 'base64',
			format: 'jpeg',
			size: {width: 400, height: 266}
		}).then(function (resp) {crop_img
			$('#wizardPicturePreview').attr('src', resp);
			$('#crop_img').attr('value', resp);
			$('#cropImagePop').modal('hide');
		});
	});
</script>

<script>
	$(document).ready(function() {
		//toggleFees('<?php echo trim($prog[0]->feetype); ?>');
		$('#category').val('<?php echo trim($prog[0]->category); ?>').selectpicker('refresh');
		$("#schedule").on("change", function() {
		  var fileName = $(this).val().split("\\").pop();
		  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
		
		$("#brochure").on("change", function() {
		  var fileName = $(this).val().split("\\").pop();
		  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
		CKEDITOR.replace('fdetails', {
			extraPlugins: 'uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
			height: 200,
			mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
		});
		CKEDITOR.replace('wlearn', {
			extraPlugins: 'uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
			height: 200,
			mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
		});
		CKEDITOR.replace('overview', {
			extraPlugins: 'uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
			height: 200,
			mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
		});
		CKEDITOR.replace('requirement', {
			extraPlugins: 'uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
			height: 200,
			mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
		});
		CKEDITOR.replace('contact_info', {
			extraPlugins: 'uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
			height: 200,
			mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
		});
		CKEDITOR.replace('placement', {
			extraPlugins: 'uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
			height: 200,
			mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
		});
	});
</script>