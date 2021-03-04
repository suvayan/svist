<?php
	if(!empty($userList)){
		echo '<ul class="list-unstyled">';
		$i=1;
		foreach($userList as $trow){
			echo '<li class="clearfix"><a href="javascript:;" id="start_chat" data-touserid="'.$trow->user_id.'" data-tousername="'.$trow->uname.'">'.$trow->uname;
			if(count(${'loggedId_'.$i})==1){
				if(${'loggedId_'.$i}[0]->status==='1'){
					echo '<span class="pull-right text-success"><i class="material-icons">fiber_manual_record</i></span>';
				}else{
					echo '<span class="pull-right text-warning"><i class="material-icons">fiber_manual_record</i></span>';
				}
			}else{
				echo '<span class="pull-right text-warning"><i class="material-icons">fiber_manual_record</i></span>';
			}
			echo '</a></li>';
			$i++;
		}
		echo '</ul>';
	}else{
		echo '<h6>No user found.</h6>';
	}
  ?>
