<?php
	//print_r($userchat);
	if(!empty($userchat)){
		echo '<ul class="list-group">';
		foreach($userchat as $ucow){
			$to_id = $ucow->to_sl;
			$from_id = $ucow->from_sl;
			$msg = trim($ucow->textmsg);
			if($from_id==$mainId){
				echo '<li class="list-group-item"><div class="alert alert-success pull-right w-75 p-2 mb-0">
                    <span>'.$msg.'<br>-'.date('d m Y h:sa',strtotime($ucow->datetime)).'</span>
                  </div></li>';
			}else{
				echo '<li class="list-group-item"><div class="alert alert-info pull-left w-75 p-2 mb-0">
                    <span>'.$msg.'<br>-'.date('d/m/Y h:sa',strtotime($ucow->datetime)).'</span>
                  </div></li>';
			}
		}
		echo '</ul>';
	}
?>
