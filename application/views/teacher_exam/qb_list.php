<ul class="list-group">
	<li class="list-group-item">
		<a href="javascript:;" onClick="getAllQuestionsList('', '');">All Questions</a>
	</li>
	<?php
		if(!empty($qbs)){
			$i=1;
			foreach($qbs as $qow)
			{
				$qbid = $qow->id;
				echo '<li class="list-group-item" id="qbc_'.$qbid.'">
						<a href="javascript:;" onClick="getAllQuestionsList(`'.$qow->name.'`, '.$qbid.')">'.$qow->name.'</a>
						<span class="pull-right">
							<a href="javascript:;" onClick="quesBankModal(`edit`, '.$qbid.')" title="Edit" class="text-success"><i class="material-icons">edit</i></a>
							<a href="javascript:;" onClick="deleteQB(`'.$qow->name.'`, '.$qbid.')" title="Delete" class="text-danger"><i class="material-icons">delete</i></a>
						</span>
					  </li>';
				$i++;
			}
		}else{
			echo '<li class="list-group-item">Create your question bank</li>';
		}
	?>
</ul>