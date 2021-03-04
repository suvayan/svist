<div class="row">
	<?php
		if(!empty($tests)){
			$i=1;
			foreach($tests as $tow)
			{
				$ttid = $tow->id;
				$title = trim($tow->title);
				$dur = trim($tow->duration);
				echo '<div class="col-md-4 mb-2" id="ttc_'.$ttid.'">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">'.$title.'</h4>
						</div>
						<div class="card-body">
							<strong>Sections: </strong>'.${'sec_'.$i}[0]->sec_count.'<br>
							<strong>Questions: </strong>'.${'sec_'.$i}[0]->ques_count.'<br>
							<strong>Marks: </strong>'.trim($tow->marks).'<br>
							<strong>Time: </strong>'.$dur.'<br>
							<strong>Status: </strong>';
				if($tow->publish!='f'){
					echo 'Published';
					if(!empty(${'ttp_'.$i})){
						$pt = (int)trim(${'ttp_'.$i}[0]->publish_type);
						echo '<a href="javascript:;" onClick="unPublishTest(`'.$title.'`, '.$ttid.')" class="text-danger pull-right">Unpublish Test?</a>';
						echo '<br><strong>Launch: </strong>';
						if($pt==1){ echo 'Anytime'; }else if($pt==2){ echo date('jS M Y',strtotime(${'ttp_'.$i}[0]->start_datetm)); }else if($pt==3){ echo date('jS M Y',strtotime(${'ttp_'.$i}[0]->start_datetm)).' - '.date('jS M Y',strtotime(${'ttp_'.$i}[0]->end_datetm)); }
					}
				}else{
					echo 'Not Published';
					echo '<a href="javascript:;" onClick="publishTestNow(`'.$title.'`, '.$ttid.', '.$dur.')" class="text-success pull-right">Publish Test?</a>';
				}
				echo '</div>
						<div class="card-footer">
							<a href="'.base_url('Teacher/testDetails/?id='.base64_encode($ttid)).'" class="btn btn-sm btn-info">View Details</a>
							<div class="d-flex pull-right">
								<a href="javascript:;" onClick="testModal(`edit`, '.$ttid.')" title="Edit" class="text-info"><i class="material-icons">edit</i></a>
								<a href="javascript:;" onClick="deleteTest(`'.$title.'`, '.$ttid.')" title="Delete" class="text-warning"><i class="material-icons">delete</i></a>
							</div>
						</div>
					</div>
				</div>';
				/*echo '<li class="list-group-item" id="ttc_'.$ttid.'">
						<a href="javascript:;" onClick="getAllQuestionsList(`'.$title.'`, '.$ttid.')">'.$title.'</a>
						<span class="pull-right">
							<a href="javascript:;" onClick="testModal(`edit`, '.$ttid.')" title="Edit" class="text-success"><i class="material-icons">edit</i></a>
							<a href="javascript:;" onClick="deleteTest(`'.$title.'`, '.$ttid.')" title="Delete" class="text-danger"><i class="material-icons">delete</i></a>
						</span>
					  </li>';*/
				$i++;
			}
		}else{
			echo '<div class="col-12"><h4 class="text-center">Create your test</h4></div>';
		}
	?>
</div>