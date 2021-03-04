<div class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-md-8">
				<div class="row">
					<div class="col-lg-12">
						<div class="card ">
							<div class="card-header card-header-info card-header-text">
							  <div class="card-text">
								<h4 class="card-title"><?php echo $prog[0]->title.' ('.$prog[0]->code.')'; ?></h4>
							  </div>
							  <!--<div class="dropdown pull-right">
								  <a href="javascript:;" class="" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="material-icons">more_vert</i>
								  </a>
								  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
									  <?php /*if($prog[0]->user_id==$_SESSION['userData']['userId']){ ?>
									  <a href="<?php echo base_url('Teacher/addProgram/?id='.base64_encode($prog[0]->id)); ?>" class="btn btn-success btn-link" data-placement="bottom">Edit Program</a>
									  <a href="<?php echo base_url('Teacher/addProgramDetails/?id='.base64_encode($prog[0]->id)); ?>" class="btn btn-success btn-link" data-placement="bottom">Program Details</a>
									  <a href="javascript:;" class="btn btn-success btn-link" data-placement="bottom">Program on Web</a>
									  <?php }*/ ?>
								  </div>
								</div>-->
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
										echo 'Total Fees: '.(($prog[0]->feetype=='Paid')? trim($prog[0]->total_fee) : $prog[0]->feetype).',   ';
										if(trim($prog[0]->total_credit)!=""){
											echo 'Total Credit: '.$prog[0]->total_credit;
										}
									?>
								</h6>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<ul class="nav nav-pills nav-pills-info justify-content-center" role="tablist">
						  <li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#link11" role="tablist">Courses
							</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#link7" role="tablist">Description
							</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#link8" role="tablist">Teachers
							</a>
						  </li>
						  <!--<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#link10" role="tablist">Students
							</a>
						  </li>-->
						  <li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#link9" role="tablist">Institute/Stream
							</a>
						  </li>
						</ul>
						<div class="tab-content tab-space tab-subcategories">
						  <div class="tab-pane" id="link7">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-md-12">
										  <div class="page-categories">
											<ul class="nav nav-pills nav-pills-warning nav-pills-icons justify-content-center" role="tablist">
											  <li class="nav-item">
												<a class="nav-link active" data-toggle="tab" href="#link1" role="tablist">
												   Overview
												</a>
											  </li>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link2" role="tablist">
												  Fees
												</a>
											  </li>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link3" role="tablist">
												  Why Learn?
												</a>
											  </li>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link4 role="tablist">
												   Requirement
												</a>
											  </li>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link5" role="tablist">
												   Contact
												</a>
											  </li>
											</ul>
											<div class="tab-content tab-space tab-subcategories">
											  <div class="tab-pane active" id="link1">
												<div class="card">
												  <div class="card-body text-justify">
													<?php 
														echo $prog[0]->overview; 
														if($prog[0]->program_brochure!=''){
															echo '<h6><a href="'.base_url().'uploads/programs/'.$prog[0]->program_brochure.'" target="_blank"><i class="material-icons">get_app</i> Brochure</a></h6>';
														}
														if($prog[0]->certificate_sample!=''){
															echo '<h6><a href="'.base_url().'uploads/programs/'.$prog[0]->certificate_sample.'" target="_blank"><i class="material-icons">grade</i> Sample Certificate</a></h6>';
														}
													?>
												  </div>
												</div>
											  </div>
											  <div class="tab-pane" id="link2">
												<div class="card">
												  <div class="card-body text-justify">
													<?php echo $prog[0]->fee_details; ?>
												  </div>
												</div>
											  </div>
											  <div class="tab-pane" id="link3">
												<div class="card">
												  <div class="card-body text-justify">
													<?php echo $prog[0]->why_learn; ?>
												  </div>
												</div>
											  </div>
											  <div class="tab-pane" id="link4">
												<div class="card">
												  <div class="card-body text-justify">
													<?php echo $prog[0]->requirements; ?>
												  </div>
												</div>
											  </div>
											  <div class="tab-pane" id="link5">
												<div class="card">
												  <div class="card-body text-justify">
													<h5 class="text-center d-flex">
														<div class="mr-5">
															<i class="material-icons">mail</i><br>
															<a href="mailto:<?php echo $prog[0]->email; ?>"> <?php echo $prog[0]->email; ?></a>
														</div>
														<div>
															<i class="material-icons">call</i><br>
															<a href="tel:<?php echo $prog[0]->mobile; ?>"> <?php echo $prog[0]->mobile; ?></a>
														</div>
													</h5>
													<?php echo $prog[0]->contact_info; ?>
												  </div>
												</div>
											  </div>
											</div>
										  </div>
										</div>
									  </div>
									</div>
								</div>
							</div>
							<!---Tab Panel 1 Ends-->
							<div class="tab-pane active" id="link11">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">All Courses [<?php echo count($procourse); ?>]</h4>
										<?php 
											$i=1; $sid = 0;
											foreach($procourse as $crow){ 
												$cid = $crow->id;
												if($sid!=$crow->sem_id){
													echo '<h6>'.trim($crow->ps_title).'</h6><hr>';
													$sid=$crow->sem_id;
												}
												echo '<a href="'.base_url('Teacher/courseDetails/?cid='.base64_encode($cid).'&prog='.base64_encode($prog_id)).'">
												  <span class="badge badge-pill badge-info p-3 mr-2 my-2">'.$crow->title.' ('.$crow->c_code.') - View Details'.'</span>
												  </a>';
												$i++;
											}
										?>
									</div>
								</div>
							</div>
							<!---Tab Panel 1 Ends-->							
							<div class="tab-pane" id="link8">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Teachers</h4>
										<div class="material-datatables">
											<table class="table table-striped table-no-bordered table-hover dtinsstrm" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th width="5%">Sl.</th>
														<th width="60%" colspan="2">Name</th>
														<th width="20%">Email</th>
														<th width="15%">Phone</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=1; foreach($pprof as $trow){ ?>
														<tr>
															<td><?php echo $i; ?></td>
															<td width="9%"><?php echo '<img src="'.base_url().$trow->photo_sm.'" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" class="avatar-dp mr-2" />'; ?></td>
															<td><?php echo '<div class="td-name">'.$trow->name.'</div>'; ?></td>
															<td><?php echo $trow->email; ?></td>
															<td><?php echo $trow->phone; ?></td>
														</tr>
													<?php $i++; } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							
							<!---Tab Panel 3 Ends-->
							<div class="tab-pane" id="link9">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Institutes</h4>
										<div class="material-datatables">
											<table class="table table-striped table-no-bordered table-hover dtinsstrm" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th width="5%">Sl.</th>
														<th width="25%">Name</th>
														<th width="30%">Website</th>
														<th width="40%">Contact</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=1; foreach($institute as $irow){ ?>
														<tr>
															<td><?php echo $i; ?></td>
															<td><?php echo $irow->title; ?></td>
															<td style="word-break:break-all;"><?php echo $irow->website; ?></td>
															<td style="word-break:break-all;"><?php echo $irow->contact_info; ?></td>
														</tr>
													<?php $i++; } ?>
												</tbody>
											</table>
										</div>
										<hr class="my-3">
										<h4 class="card-title">Streams</h4>
										<div class="material-datatables">
											<table class="table table-striped table-no-bordered table-hover dtinsstrm" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th width="5%">Sl.</th>
														<th width="20%">Name</th>
														<th width="20%">Website</th>
														<th width="15%">Institute</th>
														<th width="40%">Contact</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=1; foreach($stream as $srow){ ?>
														<tr>
															<td><?php echo $i; ?></td>
															<td><?php echo $srow->title; ?></td>
															<td style="word-break:break-all;"><?php echo $srow->website; ?></td>
															<td><?php echo $srow->institute; ?></td>
															<td style="word-break:break-all;"><?php echo $srow->contact_info; ?></td>
														</tr>
													<?php $i++; } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!---Tab Panel 3 Ends-->
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
		
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title">Notices
							<a href="javascript:;" data-toggle="modal" data-target="#noticeM" class="pull-right" style="font-size:1rem;"><i class="material-icons">add</i> Send Notice</a>
						</h4>
					</div>
					<div class="card-body">
						<div class="notice_ticker" style="height:30vh;">
							<ul class="list-group" id="notc_details">
							
							</ul>
						</div>
					</div>
					<div class="card-footer">
						<a href="<?php echo base_url('Teacher/notices'); ?>" class="btn btn-info btn-sm pull-right">Know More</a>
					</div>
				</div>
				<a href="<?php echo base_url().'Teacher/progTeachers/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Teachers</a>
				<a href="<?php echo base_url().'Teacher/progStudents/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Students</a>
			</div>
			
		</div>
	</div>
</div>
<div class="modal fade" id="noticeM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Add Notice</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmNotice" enctype="multipart/form-data">
				<div class="modal-body">
				  <div class="form-group mb-0">
					<label for="nottitle" class="text-dark">Title</label>
					<input type="text" class="form-control" name="nottitle" id="nottitle" required="true">
					<input type="hidden" name="pn_id" id="pn_id" value="0">
				  </div>	
				  <div class="form-group mb-0">
					<select name="not_prog" id="not_prog" onChange="getProgCourses(this.value, 'not_');" class="selectpicker" data-style="select-with-transition" title="Select an program*" required="true">
						<?php 
							echo '<option value="'.$prog[0]->id.'">'.$prog[0]->title.' ('.$prog[0]->code.')</option>';
						?>
					</select>
				  </div>
				  <div class="form-group mb-0">
					<select name="not_course" id="not_course" class="selectpicker" data-style="select-with-transition" title="Select an course*" required="true">

					</select>
				  </div>
				  <div class="custom-file">
					<input type="file" class="custom-file-input" name="fl_notice" id="fl_notice" accept="application/pdf, .doc, .docx">
					<label class="custom-file-label text-dark" for="fl_notice">Upload your file</label>
				  </div>
				  <div class="form-group mt-5">
					<label for="notdetails" class="text-dark">Details</label><br>
					<textarea class="form-control w-100" name="notdetails" id="notdetails"></textarea>
				  </div>
				</div>
				<div class="modal-footer">
				  <input type="reset" style="visibility:hidden">
				  <button type="submit" class="btn btn-link">Save</button>
				  
				  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/js/home.js'); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#not_prog').click();
		getProgramNotices();
	})
	$('.dtinsstrm').DataTable();
	function deleteCourse(cid)
	{
		Swal.fire({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.value) {
			  $.ajax({
				  url:baseURL+'Teacher/deleteProgCourse',
				  type: 'GET',
				  data: {id: cid},
				  success: (res)=>{
					  if(res){
						  $('#progc_'+cid).remove();
						  Swal.fire(
							  'Deleted!',
							  'Your course has been deleted.',
							  'success'
						   );
					  }else{
						  Swal.fire(
							  'Error!',
							  'Your course could not be deleted. Server error.',
							  'error'
						   );
					  }
				  },
				  error: ()=>{
					  console
				  }
			  });
		  }
		})
	}
</script>