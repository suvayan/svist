<div id="accordion" role="tablist">
	<div class="card-collapse">
		<?php
			$i=1;
			foreach($orgs as $orow){
				$org_id = $orow->id;
				echo '<div class="card-header" role="tab" id="heading'.$i.'">
						<h5 class="mb-0">
						  <a data-toggle="collapse" href="#collapse'.$i.'" aria-expanded="false" aria-controls="collapse'.$i.'" class="collapsed">
							#'.$i.' '.trim($orow->title).'
							<i class="material-icons">keyboard_arrow_down</i>
						  </a>
						</h5>
					  </div>
					  <div id="collapse'.$i.'" class="collapse" role="tabpanel" aria-labelledby="heading'.$i.'" data-parent="#accordion" style="">
						<div class="card-body">';
							$logo = trim($orow->logo);
							if($logo!=""){
								if(file_exists('./assets/img/institute/'.$logo)){
									echo '<img src="'.base_url('assets/img/institute/'.$logo).'" class="img-fluid img-thumbnail" width="100"/><br>';
								}
							}
							echo '<a href="javascript:organizationModal(`Edit`,'.$org_id.');" title="Update" id="Edit_org_'.$org_id.'" class="btn btn-success btn-sm"><i class="material-icons">edit</i> Edit Organization</a>
							<a href="javascript:departmentModal(`Add`,'.$org_id.', null, `'.trim($orow->title).'`);" title="Add" id="Add_dept_'.$org_id.'" class="btn btn-info btn-sm"><i class="material-icons">add</i> New Department</a>
							<ol type="1">';
							if(!empty(${'depts_'.$i})){
								foreach(${'depts_'.$i} as $drow){
									echo '<li class="mb-2" id="list_'.$drow->id.'">'.trim($drow->title).' 
										<a href="javascript:departmentModal(`Edit`,'.$org_id.','.$drow->id.', `'.trim($orow->title).'`)" title="Edit" id="Edit_dept_'.$drow->id.'" class="text-info"><i class="material-icons">edit</i></a>
										<a href="javascript:deleteDepartment('.$drow->id.', `'.trim($drow->title).'`)" title="Delete" class="text-danger"><i class="material-icons">delete</i></a>
										</li>';
								}
							}
					echo '</ol>
						</div>
					  </div>';
				$i++;
			}
		?>
	</div>
</div>