<?php
	if($type=='resume'){
		if(!empty($resume)){
			$ufile = trim($resume[0]->resume_heading);
			if($ufile!=""){
				if(file_exists('./'.$ufile)){
					echo '<h4 class="text-center text-danger font-weight-bold"><a href="'.base_url($ufile).'" target="_blank"><i class="material-icons">get_app</i> Resume</a>.</h4>';
				}else{
					echo '<h4 class="text-center text-danger font-weight-bold">File not found. Please update your profile <a href="'.base_url('Student/userProfile').'">here</a>.</h4>';
				}
			}else{
				echo '<h4 class="text-center text-danger font-weight-bold">Resume not upload. Please update your profile <a href="'.base_url('Student/userProfile').'">here</a>.</h4>';
			}
		}
	}else{
		if(!empty($skills)){
			foreach($skills as $row){
				echo '<span class="label label-primary mr-3">'.trim($row->name).'</span>';
			}
		}else{
			echo '<h4 class="text-center text-danger font-weight-bold">Skills not added. Please update your profile <a href="'.base_url('Student/userProfile').'">here</a>.</h4>';
		}
	}
?>