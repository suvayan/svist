<?php
	if(count($program)>0){
		$i=1;
		echo '<div class="row">';
		foreach($program as $prow){
				$fee = trim($prow->feetype);
			echo '<div class="col-lg-4 col-md-4 mb-3">
					<div class="card card-product">
					  <a href="'.base_url().'programDetails/?id='.base64_encode($prow->id).'">
					  <div class="card-header card-header-image">
						
						  <img class="img" src="'.base_url().'assets/img/banner/'.$prow->banner.'" onerror="this.src=`'.base_url().'assets/img/sample.jpg`">
						
					  </div>
					  </a>
					  <div class="card-body" style="height:150px;">
						<h4 class="card-title font-weight-bold"><a href="'.base_url().'programDetails/?id='.base64_encode($prow->id).'">'.trim($prow->title).'</a></h4>
						<div class="card-description">';
						  if(!empty(${'ins_'.$i}))
						  {
							  echo trim(${'ins_'.$i}[0]->title).', ('.$prow->yearnm.'), ';
						  }
						  $dur = intval(trim($prow->duration));
						  echo 'Duration: '.$dur.' '.trim($prow->dtype).(($dur==1)? '':'s').'
						  <h6 class="text-center">'.(($fee=='Paid')? 'Rs '.$prow->total_fee : $fee).'</h6>
						  <h6 class="text-center">Start Date: '.date('jS M Y',strtotime($prow->start_date)).'</h6>
						</div>
					  </div>
					  <div class="card-footer">
						<div class="price">
						  <h4>'.trim($prow->category).'</h4>
						</div>
					  </div>
					</div>
				  </div>';
		$i++;
		}
		echo '</div>';
	}
?>