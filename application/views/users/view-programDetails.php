<style>
#course_code::placeholder {
	color: white;
}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-lg-8 col-md-8 mx-auto">
				<div class="row">
					<div class="col-lg-12">
						<div class="card ">
							<div class="card-header card-header-primary card-header-text">
							  <div class="card-text">
								<h4 class="card-title"><?php echo $prog[0]->title.' ('.$prog[0]->code.')'; ?></h4>
							  </div>
							 <a href="<?php echo base_url().'Student/allPrograms'; ?>" class="btn btn-sm btn-primary pull-right"><i class="material-icons">list</i> All Programs</a>
							</div>
							<div class="card-body">
								<h5 style="font-variant:normal">
									
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
									?>
								 Program Type : <?php echo trim($prog[0]->ptype); ?>, Category : <?php echo trim($prog[0]->category); ?>, 
									<?php
										$dur = intval(trim($prog[0]->duration));
										echo 'Duration: '.$dur.' '.trim($prog[0]->dtype).'(s), ';
										echo 'Total Fees: '.(($prog[0]->feetype=='Paid')? trim($prog[0]->total_fee) : $prog[0]->feetype).',   ';
										if(trim($prog[0]->total_credit)!=""){
											echo 'Total Credit: '.$prog[0]->total_credit;
										}
									?>
								</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<ul class="nav nav-pills nav-pills-info justify-content-center" role="tablist">
						  <li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#link10" role="tablist">Courses
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
												  <i class="material-icons">info</i> Overview
												</a>
											  </li>
											  <?php if($prog[0]->feetype=='Paid'){ ?>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link2" role="tablist">
												  <i class="material-icons">payment</i> Fees
												</a>
											  </li>
											  <?php } ?>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link3" role="tablist">
												  <i class="material-icons">help_outline</i> Why Learn?
												</a>
											  </li>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link4 role="tablist">
												  <i class="material-icons">class</i> Requirement
												</a>
											  </li>
											  <li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#link5" role="tablist">
												  <i class="material-icons">contacts</i> Contact
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
							<!---Tab Panel 1 Ends-->
							<div class="tab-pane active" id="link10">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Courses [<?php echo count($procourse); ?>]</h4>
										<ul class="timeline timeline-simple">
											<?php $i=1; foreach($procourse as $pc){ $cid = $pc->id ?>
											<li class="timeline-inverted" id="progc_<?php echo $cid; ?>">
											  <div class="timeline-badge success">
												<?php echo $i; ?>
											  </div>
											  <div class="timeline-panel">
												<div class="timeline-heading">
												  <span class="badge badge-pill badge-info"><?php echo $pc->title.' ('.$pc->c_code.')'; ?></span>
												</div>
												<div class="timeline-body">
													<h6 class="text-dark">Duration: 6 months</h6>
													<a href="javascript:;" class="mr-2">Lectures (<?php echo $pc->lec; ?>)</a>
													<a href="javascript:;" class="mr-2">Tutorials (<?php echo $pc->tut; ?>)</a>
													<a href="javascript:;" class="mr-2">Practicals (<?php echo $pc->prac; ?>)</a>
													<?php 
														if($pc->syllabus!=''){
															echo '<a href="'.base_url().'uploads/course/'.$pc->syllabus.'" target="_blank">Shyllabus</a>';
														}
													?>
												</div>
											  </div>
											</li>
											<?php $i++; } ?>
										</ul>
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
			<!--<aside class="col-lg-4 col-md-4">
				<div class="card bg-warning text-white">
					<div class="card-body">
						<form id="frmLive">
							<input type="text" name="course_code" id="course_code" class="form-control text-white" placeholder="Enter Course code.." required="true">
							<button class="btn bg-dark btn-block" id="liveBt" type="submit">Join Online Class</button>
						</form>
					</div>
				</div>
				
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">Notices</h3>
					</div>
					<div class="card-body">
						<div class="notice_ticker" style="height:30vh;">
							<ul class="list-group" id="notc_details">
							
							</ul>
						</div>
					</div>
				</div>
				
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">Messages</h3>
					</div>
					<div class="card-body" style="height:30vh;">
						
					</div>
				</div>
			</aside>-->
			
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
	//getProgramNotices();
	$('.dtinsstrm').DataTable();
});
</script>