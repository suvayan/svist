<style>
#course_code::placeholder {
	color: white;
}
.dropdown bootstrap-select {
	width:100% !important;
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
		
			<div class="col-lg-8 col-md-8">
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">My Programs</h3>
					</div>
					<div class="card-body">
						<div id="accordion" role="tablist">
							<?php $i=1; if(count($programs)>0){ foreach($programs as $prow){ 
								$id = $prow->id;
								$title = $prow->title;
							?>
								<div class="card-collapse" id="card_<?php echo $id; ?>">
								  <div class="card-header" role="tab" id="heading<?php echo $i; ?>">
									<h5 class="mb-0">
									  <a class="collapsed" data-toggle="collapse" href="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>">
										<?php echo $i.". ".$title.' ( Academic year: '.trim($prow->yearnm).')'; ?>
										<i class="material-icons">keyboard_arrow_down</i>
									  </a>
									</h5>
								  </div>
								  <div id="collapse<?php echo $i; ?>" class="collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12">
											  <?php
													$type = trim($prow->ptype);
													$category = trim($prow->category);
													if($type!=""){
														echo $type.' Program';
													}
													if($category!=""){
														echo ', '.$category;
													}
													
													echo '<a href="'.base_url('Teacher/viewProgram/?id='.base64_encode($id)).'" class="btn btn-sm btn-success ml-2">View Details</a>';
													if(count(${'pcourses'.$i})>0){
														echo '<p>This program has the following course(s). Click to view them.</p>';
														$sid = 0;
														foreach(${'pcourses'.$i} as $pcow){
															if($sid!=$pcow->sem_id){
																echo '<h6>'.trim($pcow->ps_title).'</h6><hr>';
																$sid=$pcow->sem_id;
															}
															echo '<a href="'.base_url().'Teacher/courseDetails/?cid='.base64_encode($pcow->id).'&prog='.base64_encode($prow->id).'">'.$pcow->title.' ('.$pcow->c_code.') - '.trim($pcow->type).'</a><br>';
														}
													}else{
														echo '<p>No course added.</p>';
													}
											  ?>
											</div>
										</div>
									</div>
								  </div>
								</div>
							<?php $i++;} } ?>
						</div>
					</div>
				</div>
			</div>
			<aside class="col-lg-4 col-md-4">
				<div class="card bg-warning text-white">
					<div class="card-body">
						<form id="frmLive">
							<input type="text" name="course_code" id="course_code" class="form-control text-white" placeholder="Enter Course code.." required="true">
							<button class="btn bg-dark btn-block" id="liveBt" type="submit">Start Live Class</button>
						</form>
					</div>
				</div>
				
				<div class="card bg-secondary">
					<div class="card-body justify-content-center">
						<button class="btn bg-dark btn-block" data-toggle="modal" data-target="#schedule">Schedule Class</button>
					</div>
				</div>
				
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
			</aside>
			
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
							foreach($programs as $row){ 
								echo '<option value="'.$row->id.'">'.$row->title.' ('.$row->code.')</option>';
							}
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
<div class="modal fade" id="schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Schedule Class</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmSchedule">
			<div class="modal-body">
				<div class="form-group">
					<label for="schtitle" class="text-dark">Title</label>
					<input type="text" class="form-control" name="schtitle" id="schtitle" required="true">
				</div>
				<div class="form-group">
					<select name="sch_prog" id="sch_prog" onChange="getProgCourses(this.value, 'sch_');" class="selectpicker" data-style="select-with-transition" title="Select an program*" required="true">
						<?php 
							foreach($programs as $row){ 
								echo '<option value="'.$row->id.'">'.$row->title.' ('.$row->code.')</option>';
							}
						?>
					</select>
				  </div>
				  <div class="form-group">
					<select name="sch_course" id="sch_course" class="selectpicker" data-style="select-with-transition" title="Select an course*" required="true">
						
					</select>
				  </div>
				  <div class="form-group">
					<label for="sch_start" class="text-dark">Start Date Time</label>
					<input type="datetime-local" class="form-control" name="sch_start" id="sch_start" required="true">
				  </div>
				  <div class="form-group">
					<label for="sch_end" class="text-dark">End Date Time</label>
					<input type="datetime-local" class="form-control" name="sch_end" id="sch_end" required="true">
				  </div>
				  <div class="form group">
						<div class="togglebutton">
							<label class="text-dark">
							  Offline
							  <input type="checkbox" name="schLine" id="schLine" checked="true" value="1">
							  <span class="toggle"></span>
							  Online
							</label>
						  </div>
				  </div>
				  <div class="form-group">
					<label for="sch_online" class="text-dark">Online Link</label><br>
					<textarea class="form-control" name="sch_online" id="sch_online"></textarea>
				  </div>
				  <div class="form group">
						<div class="togglebutton">
							<label class="text-dark">
							  Notify?
							  <input type="checkbox" name="schNotify" id="schNotify" checked="true" value="1">
							  <span class="toggle"></span>
							</label>
						  </div>
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