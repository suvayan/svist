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
			
			<div class="col-md-12">
				<div class="card ">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title">Courses under "<?php echo $prog[0]->title.' ('.$prog[0]->code.')'; ?>"</h4>
					  </div>
					  <a href="<?php echo base_url().'Subadmin/programMaster'; ?>" class="btn btn-primary btn-sm pull-right"><i class="material-icons">list</i> Program List</a>
					  <a href="<?php echo base_url().'Subadmin/addCourse/?prog='.base64_encode($prog_id); ?>" class="btn btn-primary btn-sm pull-right"><i class="material-icons">add</i> Add Course</a>
					</div>
					<div class="card-body">
						<div class="row">
							<?php
								$i=1;
								$pscount = count($psems);
								if($pscount==1){
									if(!empty(${'procourse_'.$i})){ 
										foreach(${'procourse_'.$i} as $pc){ 
											$cid = $pc->id;
											echo '<div class="col-md-4" id="progc_'.$cid.'">
													<div class="card bg-light">
														<div class="card-body text-center">
															<h5 class="text-danger"><u>'.$pc->title.'</u><br>'.trim($pc->type).'</h5>
															<h6 class="text-dark">Duration: 6 months</h6>
															Lectures ('.$pc->lec.'<br>
															Tutorials ('.$pc->tut.'<br>
															Practicals ('.$pc->prac.')
														</div>
														<div class="card-footer">';
															if($pc->syllabus!=''){
																echo '<a href="'.base_url('uploads/course/'.$pc->syllabus).'" target="_blank">Syllabus</a>';
															}
															echo '<a href="'.base_url('Subadmin/addCourse/?prog='.base64_encode($prog_id).'&cid='.base64_encode($cid)).'" class="btn btn-info btn-sm pull-right"><i class="material-icons">edit</i> Edit</a>
															<button type="button" onCLick="deleteCourse('.$cid.')" class="btn btn-sm btn-warning"><i class="material-icons">delete</i> Delete</button>
														</div>
													</div>
												</div>';
										}
									}else{ echo '<h5 class="text-danger text-center">No course added yet...</h5>'; }
								}else{
									foreach($psems as $psow){
										echo '<div class="col-md-12"><h4 class="font-weight-bold bg-info text-center text-white py-3">'.trim($psow->title).'</h4></div>';
										if(!empty(${'procourse_'.$i})){ 
											foreach(${'procourse_'.$i} as $pc){ 
												$cid = $pc->id;
												echo '<div class="col-md-4" id="progc_'.$cid.'">
														<div class="card bg-light">
															<div class="card-body text-center">
																<h5 class="text-danger"><u>'.$pc->title.'</u><br>'.trim($pc->type).'</h5>
																<h6 class="text-dark">Duration: 6 months</h6>
																Lectures ('.$pc->lec.'<br>
																Tutorials ('.$pc->tut.'<br>
																Practicals ('.$pc->prac.')
															</div>
															<div class="card-footer">';
																if($pc->syllabus!=''){
																	echo '<a href="'.base_url('uploads/course/'.$pc->syllabus).'" target="_blank">Syllabus</a>';
																}
																echo '<a href="'.base_url('Subadmin/addCourse/?prog='.base64_encode($prog_id).'&cid='.base64_encode($cid)).'" class="btn btn-info btn-sm pull-right"><i class="material-icons">edit</i> Edit</a>
																<button type="button" onCLick="deleteCourse('.$cid.')" class="btn btn-sm btn-warning"><i class="material-icons">delete</i> Delete</button>
															</div>
														</div>
													</div>';
											}
										}else{ echo '<h5 class="text-danger text-center">No course added yet...</h5>'; }
										$i++;
									}
								}
							?>
						</div>
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
				  url:baseURL+'Subadmin/deleteProgCourse',
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