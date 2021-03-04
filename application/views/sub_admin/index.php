<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/croppie/croppie.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/select2/dist/css/select2.min.css" />
<style>
#upload-demo{
width: 300px;
height: 300px;
padding-bottom:25px;
}
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
</style>
<div class="loader" id="loading" style="display:none;">
	<img src="<?php echo base_url().'assets/img/loading.gif'; ?>">
</div>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="card">
					<div class="card-header card-header-info" id="card_header">
					</div>
					<div class="card-body">
						<a href="javascript:departmentAddEditModal(`Add`,<?php echo $org_name[0]->id;?>, null, `<?php echo $org_name[0]->title;?>`);" title="Add" id="Add_dept_<?php echo $org_name[0]->id;?>" class="btn btn-info btn-sm"><i class="material-icons">add</i> New Department</a>
						<div class="progress-bar progress-bar-striped progress-bar-animated" id="prg_progress" style="width: 100%; display: none;">Loading the list. Please wait...</div>
						<div id="prog_department" role="tablist">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- organization -->
<div class="modal fade" id="organizationEditModal" tabindex="-1" role="dialog" aria-labelledby="organizationEditModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content" >
      		<div class="modal-header">
        		<h5 class="modal-title" id="organizationEditModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="material-icons">clear</i>
				  </button>
      		</div>
      		<form id="frmOrg" method="POST" enctype="multipart-form-data">
			<div class="modal-body" id="organizationEditModalBody">

      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
        		<button type="submit" class="btn btn-success btn-sm ml-1">Submit</button>
      		</div>
			</form>
    	</div>
  	</div>
</div>

<!-- department -->
<div class="modal fade" id="departmentAddEditModal" tabindex="-1" role="dialog" aria-labelledby="departmentAddEditModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content" >
      		<div class="modal-header">
        		<h5 class="modal-title" id="departmentAddEditModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="material-icons">clear</i>
				  </button>
      		</div>
			<form id="frmDept" method="POST" enctype="multipart-form-data">
      		<div class="modal-body" id="departmentAddEditModalBody">

      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
        		<button type="submit" class="btn btn-success btn-sm ml-1">Submit</button>
      		</div>
			</form>
    	</div>
  	</div>
</div>

<script src="<?php echo base_url().'assets/vendor/croppie/croppie.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/sub-admin.js"></script>

<script>
	// $(document).ready(function() {
	// 	departmentList();
	// });
	$(window).on('load', ()=>{
		departmentList();
		getOrganizationName();
	});
</script>

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
	/*$('#cropImagePop').on('show', function(){
		
	});
	$('#cropImageBtn').on('click', function (ev) {
		
	});*/
	
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
