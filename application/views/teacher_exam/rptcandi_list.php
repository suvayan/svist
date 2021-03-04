<div class="col-md-12">
<?php if(!empty($testpub)){ ; ?>
	<div class="table-responsive">
	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th width="5%">Sl</th>
				<th width="25%">Candidates</th>
				<th width="25%">Contact Details</th>
				<th width="20%">Test Key</th>
				<th width="25%">Report</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
				if(!empty($candi)){
					foreach($candi as $row){
						echo '<tr>';
						echo '<td>'.$i.'</td>';
						echo '<td>';
						$photo = trim($row->photo_sm);
						if($photo!=""){
							if(file_exists('./'.$photo)){
								echo '<img src="'.base_url($photo).'" class="img-fluid pull-left mr-2" style="width:100px;"/>';
							}
						}
						echo trim($row->name).'</td>';
						echo '<td>'.trim($row->email).'<br>'.trim($row->phone).'</td>';
						if(!empty(${'rtc_'.$i})){
							echo '<td>'.trim(${'rtc_'.$i}[0]->pswd).'</td>';
							echo '<td>';
							$tsf = trim(${'rtc_'.$i}[0]->test_start_flag);
							$tef = trim(${'rtc_'.$i}[0]->test_end_flag);
							if($tsf=='f'){
								echo '<span class="text-warning font-weight-bold">Invited for Test</span>';
							}else if($tsf!='f' && $tef=='f'){
								echo '<span class="text-success font-weight-bold">Test Going On</span><br><i>on '.date('d/M/Y h:ia',strtotime(${'rtc_'.$i}[0]->test_start_time)).'</i>';
							}else if($tsf!='f' && $tef!='f'){
								echo '<span class="text-danger font-weight-bold">Test Completed</span><br><i>on '.date('d/M/Y h:ia',strtotime(${'rtc_'.$i}[0]->test_end_time)).'</i><br>';
								echo '<a href="javascript:;" class="btn btn-sm btn-warning">View Report</a>';
							}
							echo '</td>';
						}else{
							echo '<td>-</td>';
							echo '<td><button type="button" onClick="inviteSingle('.$row->id.');" class="btn btn-sm btn-warning">Invite for test</button></td>';
						}
						echo '</tr>';
						$i++;
					}
				}
			?>
		</tbody>
	</table>
	</div>
<?php }else{ echo '<h4 class="text-center">Publish the test, before viewinf reports of the candidates.</h4>'; } ?>
</div>