
<div class="content">
	<div class="container">
		<div class="row">
			
			<div class="col-lg-12">
				<?php
					if($this->session->flashdata('error')!=NULL){
						echo '<div class="alert alert-warning">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								  <i class="material-icons">close</i>
								</button>
								<span>
								  <b> Warning - </b> '.$this->session->flashdata('error').'</span>
							  </div>';
					}
					if($this->session->flashdata('success')!=NULL){
						echo '<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								  <i class="material-icons">close</i>
								</button>
								<span>
								  <b> Success - </b> '.$this->session->flashdata('success').'</span>
							  </div>';
					}
				  ?>
				<div class="card">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title">All Programs</h4>
					  </div>
					</div>
					<div class="card-body">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12">
								<?php $i=1; foreach($programs as $prow){ ?>
								<div class="card bg-light">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12 text-dark">
												<h4><u><?php echo $prow->title.' ('.$prow->code.')'; ?></u></h4>
												<?php
													echo '<h5>';
													$type = trim($prow->ptype);
													$category = trim($prow->category);
													if($type!=""){
														echo $type.' Program, ';
													}
													if($category!=""){
														echo 'Category : '.$category.', ';
													}
													$dur = intval(trim($prow->duration));
													echo 'Duration: '.$dur.' '.trim($prow->dtype).'(s), ';
													if(trim($prow->feetype)=='paid'){
														echo 'Total Fees: '.trim($prow->total_fee).',   ';
													}
													if(trim($prow->total_credit)!=""){
														echo 'Total Credit: '.$prow->total_credit;
													}
													echo '</h5>';
													if(count(${'org'.$i})>0){
														$org='';
														echo '<br>organized by';
														foreach(${'org'.$i} as $orow){
															$org.=$orow->title.', ';
														}
														echo '<h5>'.rtrim($org,", ").'</h5>';
													}
														
													if($prow->program_brochure!=''){
														echo '<h6><a href="'.base_url().'uploads/programs/'.$prow->program_brochure.'" target="_blank">Brochure</a></h6>';
													}
												?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 d-flex w-100">
												<?php
													$src = 'assets/img/blank_certificate.png';
													$cert = trim($prow->certificate_sample);
													if($cert!=""){
														if(file_exists('./uploads/programs/'.$cert)){
															$src = 'uploads/program/'.$cert;
														}
													}
												?>
												<ul class="list-group w-100">
												  <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
													Courses
													<span class="badge badge-primary badge-pill"><?php echo ${'cpsub'.$i}; ?></span>
												  </li>
												  <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
													Teachers
													<span class="badge badge-primary badge-pill"><?php echo ${'cpprof'.$i}; ?></span>
												  </li>
												  <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
													Students
													<span class="badge badge-primary badge-pill"><?php echo ${'cpstud'.$i}; ?></span>
												  </li>
												</ul>
											</div>
											<div class="col-md-6">
												<div class="card bg-warning my-0">
													<div class="card-body">
														<?php
															$type = trim($prow->type);
															if($type=='1'){
																echo '<h6>Admission Deadline: '.date('jS M Y',strtotime($prow->aend_date)).'</h6>';
															}
															echo '<h6>Start Date: '.date('jS M Y',strtotime($prow->start_date)).'</h6>';
															if($prow->end_date!=null){
																echo '<h6>End Date: '.date('jS M Y',strtotime($prow->end_date)).'</h6>';
															}
														?>
													</div>
													<div class="card-footer">
														<?php
															$type = trim($prow->type);
															if($type=='1'){
																$curdate = strtotime(date('d-m-Y'));
																$ldt = strtotime(date('d-m-Y',strtotime($prow->aend_date)));
																$sdt = strtotime(date('d-m-Y',strtotime($prow->astart_date)));
																if($sdt!=19800 || $ldt!=19800){
																	if($curdate<$sdt){
																		echo '<h5 class="text-center text-primary">Admission starts on '.date('jS M Y',strtotime($prow->astart_date)).'</h5>';
																	}else{
																		if($curdate>=$sdt && $curdate<=$ldt){
																			echo '<a href="'.base_url('Student/programAdmission/?id='.base64_encode($prow->id)).'" class="btn btn-success btn-sm">Apply for Admission</a>';
																		}else{
																			echo '<h5 class="text-center text-danger">Admission Closed</h5>';
																		}
																	}
																}else{
																	echo '<h5 class="text-center text-danger">Admission Closed</h5>';
																}
															}else if($type=='2'){
																echo '<button type="button" onClick="applyToProgram(`'.$prow->student_enroll.'`,``,`'.$prow->id.'`);" class="btn btn-success btn-sm pull-left">Apply for Learning</button>';
															}
															echo '<a href="'.base_url().'Student/viewProgramDetails/?id='.base64_encode($prow->id).'" class="btn btn-success btn-sm">Know More</a>';
														?>
													</div>
												</div>
											
											</div>
											
										</div>
									</div>
								</div>
								<?php $i++; } ?>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url().'assets/js/function.js'; ?>"></script>