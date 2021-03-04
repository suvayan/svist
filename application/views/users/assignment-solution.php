<div class="row">
	<div class="col-md-12">
		<?php if(count($assgn_sub)>0){ 
			if($assgn_sub[0]->details!=''){
				echo trim($assgn_sub[0]->details).'<br>';
			}
			
			if((count($asubfiles)>0) && (date('d-m-Y',strtotime($assgn_sub[0]->deadline))<=strtotime(date('d-m-Y')))){
				$j=1;
				foreach($asubfiles as $afrow){
					echo $j.') <a target="_blank" href="'.base_url().'uploads/stud_assign_sub/'.$afrow->file_name.'" class="mr-3"><i class="material-icons">attach_file</i> View File </a>';
					$j++;
				}
			}else{
				echo '<h5 class="text-center"><button class="btn btn-info btn-sm" onClick="openAssgnFileModel('.$assgn_sub[0]->sl.')">Upload your assignment solutions</button></h5>';
			}
		}else{ 
			echo '<h5 class="text-center"><button class="btn btn-primary btn-md" data-toggle="modal" data-target="#casModal">Submit your assignment</button></h5>';
		} ?>
	</div>
</div>
