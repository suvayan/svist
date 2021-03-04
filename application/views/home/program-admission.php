<style>
#course_code::placeholder {
	color: white;
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
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9">
				<div class="banner-text">
					<?php 
						echo '<h3 class="font-weight-bold">'.$prog[0]->title.'</h3>';
						if(!empty($institute)){
							if($institute[0]->logo!=""){
								if(file_exists('./assets/img/institute/'.$institute[0]->logo)){
									echo '<img src="'.base_url('assets/img/institute/'.$institute[0]->logo).'" class="img-thumbnail" style="width: 100px;"/>';
								}
							}
							
							echo '<h4 class="font-weight-bold">'.$institute[0]->title;
							if(!empty($stream)){
								echo ', '.$stream[0]->title;
							}
							echo '</h4>';
						}
						
						echo '<h5 class="font-weight-bold">';
								$type = trim($prog[0]->ptype);
								$category = trim($prog[0]->category);
								if($type!=""){
									echo $type;
								}
								if($category!=""){
									echo ', '.$category.', ';
								}
								$fee = intval(trim($prog[0]->total_fee));
								$feetype = trim($prog[0]->feetype);
								$credit = trim($prog[0]->total_credit);
								$dur = intval(trim($prog[0]->duration));
								if($dur!=0){
									echo 'Duration: '.$dur.' '.trim($prog[0]->dtype).'(s),   '; 
								}
								echo (($feetype=='Paid')? 'Total Fees: Rs '.$fee : 'Free');
								if($credit!=""){
									echo ', Total Credit: '.$credit;
								}	
						echo '</h5>';
					?>
				</div>
				<div class="wizard-container">
				  <div class="card card-wizard" data-color="blue" id="wizardProfile">
					<form action="#" method="POST" id="frmApply">
					  <div class="card-header text-center">
						<h3 class="card-title">
						  Register Now
						</h3>
						<h5 class="card-description">This information will let us know more about you.</h5>
					  </div>
					  <div class="wizard-navigation">
						<ul class="nav nav-pills">
						  <li class="nav-item">
							<a class="nav-link active" href="#about" data-toggle="tab" role="tab">
							  Basic
							</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#account" data-toggle="tab" role="tab">
							  Education
							</a>
						  </li>
						  <!--<li class="nav-item">
							<a class="nav-link" href="#address" data-toggle="tab" role="tab">
							  Address
							</a>
						  </li>-->
						</ul>
					  </div>
					  <div class="card-body">
						<div class="tab-content">
						  <div class="tab-pane active" id="about">
							<h5 class="info-text"> Already have an account? Please login <a href="javascript:;" data-toggle="modal" data-target="#logModel" class="btn btn-sm btn-primary">here</a></h5>
							<div class="row">
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
									  <div class="input-group-prepend">
										<span class="input-group-text">
										  <i class="material-icons">face</i>
										</span>
									  </div>
									  <div class="form-group">
										<label for="fname" class="bmd-label-floating">First Name <span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="fname" name="fname">
										<input type="hidden" name="pid" id="pid" value="<?php echo $prog[0]->id; ?>"/>
									  </div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
									  <div class="input-group-prepend">
										<span class="input-group-text">
										  <i class="material-icons">record_voice_over</i>
										</span>
									  </div>
									  <div class="form-group">
										<label for="lname" class="bmd-label-floating">Last Name <span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="lname" name="lname">
									  </div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
									  <div class="input-group-prepend">
										<span class="input-group-text">
										  <i class="material-icons">mail</i>
										</span>
									  </div>
									  <div class="form-group">
										<label for="email" class="bmd-label-floating">Email <span class="text-danger">*</span></label>
										<input type="email" class="form-control" id="email" name="email">
									  </div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group form-control-lg">
									  <div class="input-group-prepend">
										<span class="input-group-text">
										  <i class="material-icons">phone</i>
										</span>
									  </div>
									  <div class="form-group">
										<label for="phone" class="bmd-label-floating">Mobile <span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="phone" name="phone">
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
											<label for="dob" class=""> Date of Birth <span class="text-danger">*</span></label>
											<input type="date" class="form-control" name="dob" id="dob">
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
											<select class="selectpicker" data-style="select-with-transition" name="gender" id="gender" title="Choose Gender <span class='text-danger'>*</span>">
												<option value="M">Male </option>
												<option value="F">Female</option>
												<option value="O">Others</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="newpass" class="bmd-label-floating">Password <span class="text-danger">*</span></label>
										<input type="password" class="form-control" id="newpass" name="newpass">
									  </div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="cnfpass" class="bmd-label-floating">Confirm Password <span class="text-danger">*</span></label>
										<input type="password" class="form-control" id="cnfpass" name="cnfpass">
									  </div>
								</div>
							</div>
						  </div>
						  <div class="tab-pane" id="account">
							<button type="button" onClick="addMore();" class="btn btn-info btn-sm"><i class="material-icons">add</i> Add Academic</button>
							<input type="hidden" name="educount" id="educount" value="0"/>
							<div class="border-top p-2" id="fldset_0">
								<div class="row justify-content-center">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="board_0" class="bmd-label-floating">Board/University <span class="text-danger">*</span></label>
											<input type="text" name="board_0" id="board_0" class="form-control" required>
										  </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<select class="selectpicker" name="degree_0" id="degree_0" data-style="select-with-transition" title="Select degree" data-size="5">
												<?php 
													if(!empty($degree)){
														foreach($degree as $drow){
															echo '<option value="'.$drow->id.'">'.$drow->degree_name.' ('.$drow->short.')</option>';
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
											<label for="org_0" class="bmd-label-floating">College/School Name<span class="text-danger">*</span></label>
											<input type="text" name="org_0" id="org_0" class="form-control" required>
										  </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<div class="togglebutton">
												<label>
												  Present
												  <input type="checkbox" name="status_0" id="status_0" onChange="toggleInfo(1)" value="1">
												  <span class="toggle"></span>
												  Completed
												</label>
											  </div>
										</div>
									</div>
								</div>
								<div class="row" style="display:none" id="moreInfo_0">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="passout_0" class="bmd-label-floating">Passout <span class="text-danger">*</span></label>
											<input type="text" name="passout_0" id="passout_0" digits="true" maxlength="4" minlength="4" class="form-control" required>
										  </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="marks_0" class="bmd-label-floating">CGPA/Percentage <span class="text-danger">*</span></label>
											<input type="number" name="marks_0" id="marks_0" class="form-control" required>
										  </div>
									</div>
								</div>
							</div>
							<span id="edu_more"></span>
							
						  </div>
						</div>
					  </div>
					  <div class="card-footer">
						<div class="mr-auto">
						  <input type="button" class="btn btn-previous btn-fill btn-default btn-wd disabled" name="previous" value="Previous">
						  <input type="reset" value="reset" style="display:none;"/>
						</div>
						<div class="ml-auto">
						  <input type="button" class="btn btn-next btn-fill btn-rose btn-wd" name="next" value="Next">
						  <input type="submit" class="btn btn-finish btn-fill btn-rose btn-wd" id="btnApply" name="finish" value="Finish" style="display: none;">
						</div>
						<div class="clearfix"></div>
					  </div>
					</form>
				  </div>
				</div>
				
			</div>
			<aside class="col-md-3 order-last">
				<div class="card mt-0" style="background:transparent">
					<div class="card-body">
						<div class="card mt-0">
							<div class="card-body">
								<small>Email</small><br>
								<a href="mailto:<?php echo $prog[0]->email; ?>"> <?php echo $prog[0]->email; ?> | <i class="material-icons">email</i></a>
								<br><br>
								<small>Mobile</small><br>
								<a href="tel:<?php echo $prog[0]->mobile; ?>"> <?php echo '+91 - '.$prog[0]->mobile; ?> | <i class="material-icons">phone</i></a>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<?php
									$curdate = strtotime(date('d-m-Y'));
									$ldt = strtotime(date('d-m-Y',strtotime($prog[0]->aend_date)));
									$sdt = strtotime(date('d-m-Y',strtotime($prog[0]->astart_date)));
									if($sdt!=19800 || $ldt!=19800){
										if($curdate>=$sdt && $curdate<=$ldt){
											echo '<h5 class="">Deadline: '.date('jS M Y',strtotime($prog[0]->astart_date)).'</h5>';
										}else{
											echo '<h4 class="text-center text-danger">Admission Closed</h4>';
										}
									}else{
										echo '<h4 class="text-center text-danger">Admission Closed</h4>';
									}
									$criteria = trim($prog[0]->criteria);
									if($criteria!=""){
										echo '<h6>Criteria</h6>'.$criteria;
									}
								?>
								
							</div>
						</div>
					</div>
				</div>
			</aside>
		</div>
	</div>
</div>
<div class="modal fade" id="logModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Login Form</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<form id="frmProgLogin" mehtod="POST">
		<div class="modal-body">
			<div class="form-group">
				<label for="username">Email Address</label>
				<input type="email" class="form-control" name="username" id="username"/>
				<input type="hidden" name="prog_id" id="prog_id" value="<?php echo $prog[0]->id; ?>" required="true" email="true"/>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control" name="password" id="password" required="true"/>
			</div>
		</div>
		<div class="modal-footer">
		  <button type="submit" class="btn btn-link" id="btnLogin">Login</button>
		  <input type="reset" style="visibility:hidden;"/>
		  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
		</div>
		</form>
	  </div>
	</div>
</div>
<script src="<?php echo base_url('assets/js/admission.js'); ?>"></script>
<script>
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