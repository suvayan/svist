<?php
	$submit = ($prog[0]->id=='0')? 'Add' : 'Update';
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/select2/dist/css/select2.min.css" />
<style>
.form-error{
	color:red;
}
</style>
<div class="content">
	<div class="container-fluid">
		<form action="<?php echo base_url('Teacher/cuProgram'); ?>" enctype="multipart/form-data" id="frmProgram" method="POST" style="width: 100%;">
		<div class="row">
			
			<div class="col-md-9 mx-auto">
				<div class="card">
					<div class="card-header card-header-primary card-header-icon">
					  <div class="card-icon">
						<i class="material-icons">create</i>
					  </div>
					  <h4 class="card-title"><?php echo $submit; ?> Program <small class="text-danger">*'s are important</small></h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
								  <label for="title">Academic Year <span class="text-danger">*</span></label>
								  <select class="selectpicker" data-style="select-with-transition" data-title="Select Academic Year" name="aca_year" id="aca_year" data-size="5" required="true">
									<?php
										if(!empty($ayr)){
											foreach($ayr as $ayow){
												echo '<option value="'.$ayow->sl.'" '.(($prog[0]->aca_year==$ayow->sl)? 'selected' : '').'>'.$ayow->yearnm.'</option>';
											}
										}
									?>
								  </select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
								  <label for="title">Department(s) <span class="text-danger">*</span></label>
								  <select class="selectpicker" data-style="select-with-transition" data-title="Select Department" name="dept_id" id="dept_id" required="true">
									<?php
										if(!empty($streams)){
											foreach($streams as $stow){
												echo '<option value="'.$stow->id.'" '.(($prog[0]->stream_id==$stow->id)? 'selected' : '').'>'.$stow->title.'</option>';
											}
										}
									?>
								  </select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
								  <label for="title">Program Title <span class="text-danger">*</span></label>
								  <input type="text" class="form-control" id="title" name="title" value="<?php echo trim($prog[0]->title); ?>">
								  <input type="hidden" id="pid" name="pid" value="<?php echo trim($prog[0]->id); ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 form-group">
								<div class="checkbox-radios">
									<div class="form-check form-check-inline">
									  Promote Admission? 
									  <label class="form-check-label text-dark ml-2"> 
										<input class="form-check-input" type="radio" value="1" <?php if(trim($prog[0]->type)=='1'){ echo 'checked'; }; ?> name="type" id="1"> Yes
										<span class="form-check-sign">
										  <span class="check"></span>
										</span>
									  </label>
									</div>
									<div class="form-check form-check-inline">
									  <label class="form-check-label text-dark">
										<input class="form-check-input" type="radio" <?php if(trim($prog[0]->type)=='2'){ echo 'checked'; }; ?> value="2" name="type" id="2"> No
										<span class="form-check-sign">
										  <span class="check"></span>
										</span>
									  </label>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="title">Total Credit</label>
									<input type="text" class="form-control" id="credit" name="credit" value="<?php echo trim($prog[0]->total_credit); ?>">
								</div>
							</div>
						</div>
						<div class="row">	
							<div class="col-sm-6">
								<div class="form-group">
									<label for="sdate">Total seats </label>
									<input type="number" name="total_seat" id="total_seat" value="<?php echo $adm[0]->total_seat; ?>" class="form-control">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="edate">Total Hours</label>
									<input type="number" name="total_hrs" id="total_hrs" value="<?php echo $adm[0]->prog_hrs; ?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">	
							<div class="col-sm-6">
								<div class="form-group">
									<label for="sdate">Start Date <span class="text-danger">*</span></label>
									<input type="date" name="sdate" id="sdate" value="<?php echo $prog[0]->start_date; ?>" class="form-control">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="edate">End Date <span class="text-danger">*</span></label>
									<input type="date" name="edate" id="edate" value="<?php echo $prog[0]->end_date; ?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<select class="selectpicker" data-style="select-with-transition" name="category" id="category" data-title="Select one category <span class='text-danger'>*</span>">
										<option value="Corporate Program" <?php echo (trim($prog[0]->category)=='Corporate Program')? 'selected':''; ?>>Corporate Program</option>
										<option value="Training Program" <?php echo (trim($prog[0]->category)=='Training Program')? 'selected':''; ?>>Training Program</option>
										<option value="Degree Program (Graduate)" <?php echo (trim($prog[0]->category)=='Degree Program (Graduate)')? 'selected':''; ?>>Degree Program (Graduate)</option>
										<option value="Degree Program (Post Graduate)" <?php echo (trim($prog[0]->category)=='Degree Program (Post Graduate)')? 'selected':''; ?>>Degree Program (Post Graduate)</option>
										<option value="Diploma Program" <?php echo (trim($prog[0]->category)=='Diploma Program')? 'selected':''; ?>>Diploma Program</option>
										<option value="Tutor" <?php echo (trim($prog[0]->category)=='Tutor')? 'selected':''; ?>>Tutor</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<select class="selectpicker" data-style="select-with-transition" name="prog_type" id="prog_type" data-title="Select one type <span class='text-danger'>*</span>">
										<option value="Online" <?php echo (trim($adm[0]->ptype)=='Online')? 'selected':''; ?>>Online Program</option>
										<option value="Regular" <?php echo (trim($adm[0]->ptype)=='Regular')? 'selected':''; ?>>Regular Program</option>
										<option value="Distance" <?php echo (trim($adm[0]->ptype)=='Distance')? 'selected':''; ?>>Distance Program</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="title">Program Code <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="pcode" name="pcode" value="<?php echo trim($prog[0]->code); ?>">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									 <label for="duration">Duration <span class="text-danger">*</span></label>
									 <div class="input-group">
										<div class="input-group-prepend">
											<input type="text" name="duration" id="duration" class="form-control" value="<?php echo (int)trim($prog[0]->duration); ?>">
											<select class="custom-select custom-select-sm" name="dtype" id="dtype">
												<option value="">Duration <span class="text-danger">*</span></option>
												<option value="day" <?php echo (trim($prog[0]->dtype)=='day')? 'selected':''; ?>>Days</option>
												<option value="month" <?php echo (trim($prog[0]->dtype)=='month')? 'selected':''; ?>>Months</option>
												<option value="year" <?php echo (trim($prog[0]->dtype)=='year')? 'selected':''; ?>>Years</option>
											</select>
										</div>
									 </div>
								</div>
							</div>
						</div>
						<?php /* ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									 <div class="checkbox-radios">
										<div class="form-check form-check-inline">
										  <label class="form-check-label text-dark">
											<input class="form-check-input" type="radio" <?php echo ($prog[0]->feetype=='Free')? 'checked' : ''; ?> value="Free" name="feetype" id="free"> Free
											<span class="form-check-sign">
											  <span class="check"></span>
											</span>
										  </label>
										</div>
										<div class="form-check form-check-inline">
										  <label class="form-check-label text-dark">
											<input class="form-check-input" type="radio" <?php echo ($prog[0]->feetype=='Paid')? 'checked' : ''; ?> value="Paid" name="feetype" id="paid"> Paid
											<span class="form-check-sign">
											  <span class="check"></span>
											</span>
										  </label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 d-flex">
								<div class="form-group mr-2">
									 <label for="fees">Total Fees</label>
									<input type="text" name="fees" id="fees" class="form-control" value="<?php echo trim($prog[0]->total_fee); ?>" disabled>
								</div>
								<div class="form-group">
									<label for="fdetails">Discount (If any)</label>
									<input type="number" name="rebate" id="rebate" value="<?php echo $adm[0]->discount; ?>" class="form-control">
								</div>
							</div>
						</div>
						<?php */ ?>
						<div class="row mb-3">
							<div class="col-sm-12">
								<div class="form-group">
									 <label for="overview">Overview</label><br>
									<textarea name="overview" id="overview" class="form-control" cols="80" rows="5"><?php echo $prog[0]->overview; ?></textarea>
								</div>
							</div>
						</div>
						<div class="justify-content-center text-center">
							<button type="submit" class="btn btn-primary btn-md pull-right"><?php echo $submit; ?></button>
						</div>
					</div>
				</div>
			</div>
			<aside class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Contacts <small class="text-danger">*</small></h4>
						<div class="form-group">
							 <label for="email">Email <span class="text-danger">*</span></label>
							 <input type="email" name="email" id="email" class="form-control" value="<?php echo $prog[0]->email; ?>">
						</div>
						<div class="form-group">
							 <label for="phone">Phone <span class="text-danger">*</span></label>
							 <input type="text" name="phone" id="phone" class="form-control" value="<?php echo trim($prog[0]->mobile); ?>">
						</div>
					</div>
				</div>
				<div class="card" id="sem_details" style="display:none;">
					<div class="card-body">
						<div class="form-group">
							<select class="selectpicker" data-style="select-with-transition" data-title="Select Semester type" name="semtype" id="semtype">
								<option value="12" <?php if(trim($adm[0]->sem_type)=='12'){ echo 'selected'; } ?>>Yearly</option>
								<option value="6" <?php if(trim($adm[0]->sem_type)=='6'){ echo 'selected'; } ?>>Half-Yearly</option>
							</select>
						</div>
					</div>
				</div>
				<div class="card" id="ad_details" style="display:none;">
					<div class="card-body">
						<h4 class="card-title">Admission Info. <small class="text-danger">*</small></h4>
						<div class="form-group">
							<select class="selectpicker" data-style="select-with-transition" name="apply_type" id="apply_type" title="Single Select <span class='text-danger'>*</span>">
								<option value="0" <?php if($adm[0]->apply_type=='0'){ echo 'selected'; } ?>>All Approved</option>
								<option value="1" <?php if($adm[0]->apply_type=='1'){ echo 'selected'; } ?>>Selective Approved</option>
							</select>
						</div>
						<div class="form-group" id="stype" style="display:none;">
							<?php
								if(isset($adm[0]->screen_type)){
									$st = explode(',', $adm[0]->screen_type);
								}else{
									$st = array();
								}
							?>
							<select class="selectpicker" data-style="select-with-transition" title="Screen type <span class='text-danger'>*</span>" multiple name="screen_type[]" id="screen_type">
								<option value="0" <?php if(in_array(0,$st)){ echo 'selected'; } ?>>Manual Checking</option>
								<option value="1" <?php if(in_array(1,$st)){ echo 'selected'; } ?>>Online Exam</option>
								<option value="2" <?php if(in_array(2,$st)){ echo 'selected'; } ?>>Interview</option>
							</select>
						</div>
						<div class="form-group">
							<label for="fdetails">Start Date <span class="text-danger">*</span></label>
							<input type="date" name="adstart" id="adstart" value="<?php echo $adm[0]->astart_date; ?>" class="form-control">
						</div>
						<div class="form-group">
							<label for="fdetails">End Date <span class="text-danger">*</span></label>
							<input type="date" name="adend" id="adend" value="<?php echo $adm[0]->aend_date; ?>" class="form-control">
						</div>
						<div class="form-group">
							<label for="fdetails">Criteria <span class="text-danger">*</span></label>
							<textarea name="criteria" id="criteria" class="form-control"><?php echo $adm[0]->criteria; ?></textarea>
						</div>
					</div>
				</div>
			</aside>
		</div>
		</form>
	</div>
</div>
<script src="<?php echo base_url().'assets/js/program.js'; ?>"></script>
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/ckeditor.js"></script><script>
	$(document).ready(function() {
		toggleFees('<?php echo trim($prog[0]->feetype); ?>');
		toggleAdmission('<?php echo trim($prog[0]->type); ?>');
		toggleScreenType('<?php echo trim($adm[0]->apply_type); ?>');
		toggleSemType('<?php echo trim($prog[0]->dtype); ?>');
		$('#category').val('<?php echo trim($prog[0]->category); ?>').selectpicker('refresh');
		CKEDITOR.replace('overview', {
			extraPlugins: 'uploadimage,colorbutton,colordialog,autoembed,embedsemantic,mathjax,codesnippet,font,justify,table,specialchar, tabletools, uicolor',
			height: 200,
			mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
		});
	});
	
	$('input[type=radio][name=feetype]').on('change', ()=>{
		var radioValue = $('input[type=radio][name=feetype]:checked').val();
		toggleFees(radioValue);
	});
	function toggleFees(rvalue)
	{
		if(rvalue!=''){
			if(rvalue=='Free')
			{
				$('#fees').attr('disabled', true);
				$('#rebate').attr('disabled', true);
			}else{
				$('#fees').removeAttr('disabled');
				$('#rebate').removeAttr('disabled');
			}
		}
	}
	
	$('input[type=radio][name=type]').on('change', ()=>{
		var radioValue = $('input[type=radio][name=type]:checked').val();
		toggleAdmission(radioValue);
	});
	function toggleAdmission(rvalue)
	{
		if(rvalue!=''){
			if(rvalue!='2')
			{
				$('#ad_details').show();
			}else{
				$('#ad_details').hide();
			}
		}
	}
	$('#apply_type').on('change', ()=>{
		toggleScreenType($('#apply_type').val());
	});
	function toggleScreenType(at_id)
	{
		if(at_id==0){
			$('#stype').hide();
		}else{
			$('#stype').show();
		}
	}
	$('#dtype').on('change', ()=>{
		toggleSemType($('#dtype').val());
	});
	function toggleSemType(dtype)
	{
		if(dtype=='year'){
			$('#sem_details').show();
		}else{
			$('#sem_details').hide();
		}
	}
</script>