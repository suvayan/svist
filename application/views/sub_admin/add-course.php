<style>
.form-error{
	color:red;
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
			<?php if($this->session->flashdata('success')){ ?>
				<script>
					$.notify({
						icon:"add_alert",
						message: <?php echo $this->session->flashdata('success'); ?>},
						{type:'success',
						timer:3e3,
						placement:{from:'top',align:'right'}
					});
				</script>
			<?php } if($this->session->flashdata('error')){ ?>
				<script>
					$.notify({
						icon:"add_alert",
						message: <?php echo $this->session->flashdata('error'); ?>},
						{type:'danger',
						timer:3e3,
						placement:{from:'top',align:'right'}
					});
				</script>
			<?php } ?>
			<div class="col-md-10 mx-auto">
				<div class="card ">
					<div class="card-header card-header-primary card-header-icon">
					  <div class="card-icon">
						<i class="material-icons">chrome_reader_mode</i>
					  </div>
					  <h4 class="card-title">Course <small class="text-danger">*'s are mandatory</small>
					  <a href="<?php echo base_url().'Subadmin/viewProgCourse/?pid='.base64_encode($cd[0]->prog_id); ?>" class="btn btn-sm btn-primary pull-right"><i class="material-icons">list</i> Course List</a>
					  </h4>
					</div>
					<div class="card-body ">
						
						<form method="POST" enctype="multipart/form-data" id="frmCourse">
							<div class="row mb-3 justify-content-center">
								<div class="col-sm-6">
									<div class="form-group">
										<select class="selectpicker" data-style="select-with-transition" data-title="Select Semester*" name="semid" id="semid">
											<?php
												/*if(!empty($sems)){
													$csems = count($sems);
													if($csems==1){
														echo '<option value="'.$sems[0]->id.'" selected>'.$sems[0]->title.'</option>';
													}else{
														
													}
												}*/
												foreach($sems as $ssow){
													if($cd[0]->sem_id==$ssow->id){
														echo '<option value="'.$ssow->id.'" selected>'.$ssow->title.'</option>';
													}else{
														echo '<option value="'.$ssow->id.'">'.$ssow->title.'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
									  <label for="title" class="text-dark">Course Title*</label>
									  <input type="text" class="form-control" id="title" name="title" value="<?php echo $cd[0]->title; ?>"/>
									  <input type="hidden" name="cid" id="cid" value="<?php echo $cd[0]->id; ?>"/>
									  <input type="hidden" name="prog_id" id="prog_id" value="<?php echo $cd[0]->prog_id; ?>"/>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-6">
									<div class="form-group">
										<select class="selectpicker" data-style="select-with-transition" name="crtype" id="crtype" data-title="Select type*">
											<option value="Compulsory" <?php if(trim($cd[0]->type)=='Compulsory'){ echo 'selected'; } ?>>Compulsory</option>
											<option value="Elective" <?php if(trim($cd[0]->type)=='Elective'){ echo 'selected'; } ?>>Elective</option>
											<option value="Selective" <?php if(trim($cd[0]->type)=='Selective'){ echo 'selected'; } ?>>Selective</option>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="title" class="text-dark">Course Code*</label>
										<input type="text" class="form-control" id="ccode" name="ccode" value="<?php echo $cd[0]->c_code; ?>"/>
									</div>
								</div>
							</div>
							
							<div class="row mb-3">
								<div class="col-sm-6">
									<div class="form-group">
										 <label for="sdate" class="text-dark">Start Date*</label>
										 <input type="datetime-local" name="sdate" id="sdate" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($cd[0]->start_date)); ?>"/>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										 <label for="edate" class="">End Date*</label>
										 <input type="datetime-local" name="edate" id="edate" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($cd[0]->end_date)); ?>"/>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-4">
									<div class="form-group">
										 <select name="lecture" id="lecture" class="selectpicker" data-style="select-with-transition" data-size="5" title="Total lectures*">
											<?php 
												for($i=1; $i<=20; $i++){
													if(trim($cd[0]->lec)==$i){
														echo '<option value="'.$i.'" selected>'.$i.'</option>';
													}else{
														echo '<option value="'.$i.'">'.$i.'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										 <select name="tutorial" id="tutorial" class="selectpicker" data-style="select-with-transition" data-size="5" title="Total tutorials*">
											<?php 
												for($i=1; $i<=20; $i++){
													if(trim($cd[0]->tut)==$i){
														echo '<option value="'.$i.'" selected>'.$i.'</option>';
													}else{
														echo '<option value="'.$i.'">'.$i.'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										 <select name="practical" id="practical" class="selectpicker" data-style="select-with-transition" data-size="5" title="Total practicals*">
											<?php 
												for($i=1; $i<=20; $i++){
													if(trim($cd[0]->prac)==$i){
														echo '<option value="'.$i.'" selected>'.$i.'</option>';
													}else{
														echo '<option value="'.$i.'">'.$i.'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="title" class="text-dark">Total Credit*</label>
										<input type="text" class="form-control" id="ccredit" name="ccredit" value="<?php echo $cd[0]->total_credit; ?>"/>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="syllabus" id="syllabus" accept="*pdf, *doc, *docs, *"/>
										<label class="custom-file-label text-dark" for="syllabus">Choose course syllabus</label>
									  </div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="fdetails" class="text-dark">Overview</label><br>
										<textarea name="overview" id="overview" class="form-control" cols="80" rows="5"><?php echo $cd[0]->overview; ?></textarea>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-12">
									<div class="form-group">
										 <label for="fdetails" class="text-dark">Importance</label><br>
										<textarea name="importance" id="importance" class="form-control" cols="80" rows="5"><?php echo $cd[0]->importance; ?></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<input type="reset" style="visibility:hidden;"/>
									<button type="submit" class="btn btn-primary btn-md pull-right">Save</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<script src="<?php echo base_url().'assets/js/course.js'; ?>"></script>
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/ckeditor.js"></script>
<script>
	CKEDITOR.replace('overview', {
		extraPlugins: 'easyimage,uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
		height: 200,
		mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
	});
	CKEDITOR.replace('importance', {
		extraPlugins: 'easyimage,uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
		height: 200,
		mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
	});
	
	$("#syllabus").on("change", function() {
	  var fileName = $(this).val().split("\\").pop();
	  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});
</script>