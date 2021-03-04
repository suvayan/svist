<?php
	//print_r();
	if(count($usms)>0)
	{
		$i=1;
		foreach($usms as $usow){
			$uname = trim($usow->first_name." ".$usow->last_name);
			echo '<div class="card">
				<div class="card-body">'.$uname.'<button type="button" id="start_chat" data-touserid="'.$usow->id.'" data-tousername="'.$uname.'" class="btn btn-info btn-sm pull-right">Reply</button>';
				if(${'unread_'.$i}!=0){
					echo '<br><span class="label label-danger font-weight-bold">You have '.${'unread_'.$i}.' unread message/s</span>';
				}
			echo '</div></div>';
			$i++;
		}
	}else{
		echo '<div class="card"><div class="card-body"><h4 class="text-center">Click on any user to start your chat.</h4></div></div>';
	}
  ?>
