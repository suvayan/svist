<style>
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
			
			<div class="col-md-8">
				<div class="card ">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title"><?php echo $prog[0]->title.' ('.$prog[0]->code.')'; ?></h4>
					  </div>
					</div>
					<div class="card-body">
						<h5>
							<button class="btn btn-success btn-sm"><?php echo $prog[0]->type; ?></button>
							<button class="btn btn-sm btn-info"><?php echo $prog[0]->category; ?></button>
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
						</h5>
						<h5>
							<?php
								$dur = (int)trim($prog[0]->duration);
								if($dur<=12){
									echo 'Duration: '.$dur.' months;   ';
								}else{
									$yr = $dur/12;
									$mon = $dur%12;
									echo 'Duration: '.(($yr==1)?$yr.' year':$yr.' years').(($mon!=0)? ' '.$mon.' months;':'');
								}
								echo 'Total Fees: '.$prog[0]->total_fee.';   ';
								echo 'Total Credit: '.$prog[0]->total_credit;
							?>
						</h5>
					</div>
				</div>
				<div class="card mt-0">
					<div class="card-header">
						<h4 class="card-title">All Courses [<?php echo count($procourse); ?>]
						<a href="<?php echo base_url().'Teacher/addCourse/?prog='.base64_encode($prog_id); ?>" class="btn btn-primary btn-sm pull-right"><i class="material-icons">add</i> Add Course</a>
						</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<?php foreach($procourse as $pc){ $cid = $pc->id; ?>
								<div class="col-md-6" id="progc_<?php echo $cid; ?>">
									<div class="card bg-light">
										<div class="card-body text-center">
											<h5 class="text-danger"><u><?php echo $pc->title; ?></u></h5>
											<h6 class="text-dark">Duration: 6 months</h6>
											<a href="#" class="mr-2">Lectures (<?php echo $pc->lec; ?>)</a>
											<a href="#" class="mr-2">Tutorials (<?php echo $pc->tut; ?>)</a>
											<a href="#" class="mr-2">Practicals (<?php echo $pc->prac; ?>)</a>
											<a href="#" class="mr-2">Quiz (5)</a>
											<a href="#" class="mr-2">Students (80)</a>
											<a href="#" class="">Teachers (3)</a>
										</div>
										<div class="card-footer">
											<?php 
												if($pc->syllabus!=''){
													echo '<a href="'.base_url().'uploads/course/'.$pc->syllabus.'" target="_blank">Shyllabus</a>';
												}
											?>
											<a href="<?php echo base_url().'Teacher/courseDetails/?cid='.base64_encode($cid).'&prog='.base64_encode($prog_id); ?>" class="btn btn-info btn-sm pull-right">View Details</a>
											<button type="button" onCLick="deleteCourse(<?php echo $cid; ?>)" class="btn btn-sm btn-warning"><i class="material-icons">delete</i> Delete</button>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<a href="<?php echo base_url().'Teacher/viewProgram/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm " style="margin-top:30px;">Go Back</a>
				<a href="<?php echo base_url().'Teacher/progOrgStrm/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Institute/Stream</a>
				<a href="<?php echo base_url().'Teacher/progTeachers/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Teachers</a>
				<a href="<?php echo base_url().'Teacher/progStudents/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Students</a>
				<div class="card">
					<div class="card-header card-header-warning">
						<h4 class="card-title">Notification</h4>
					</div>
					<div class="card-body">
						
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
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